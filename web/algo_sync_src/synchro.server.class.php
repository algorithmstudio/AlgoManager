<?php 

use \DateTime;

Class Server
{
	private $pdo;
	private $server;
	private $folder;
	private $apacheFolder;

	public function __construct($pdo, $server_id, $folder, $apacheFolder)
	{
		$this->pdo 			= $pdo;
		$this->server 		= $server_id;
		$this->folder 		= $folder;
		$this->apacheFolder = $apacheFolder;
	}

	public function sendApps()
    {
        $current 		= $this->procOpen( 'dpkg -l' );

        $array   		= explode( 'ii ', $current );
        $lenght  		= count( $array );
        $server_content = $this->pdo->query('SELECT * FROM Application WHERE server_id = '. $this->server .'');
        $apps_name = "";

        for($i = 1; $i < $lenght; $i++)
        {
            $chaine = preg_replace("(  +)", "_", str_replace(array("\t", "\r", "\n"), " ", $array[$i]));
            $data   = explode("_", $chaine);

            $description = (!isset($data[2])) ? "" : $data[2] ;
            $updated 	 = false;

            foreach ($server_content as $a)
            {
                if( $a->name == $data[0] )
                {
                    $updated = true;

                    if( $data[1] != $a->version )
                    {
                        $this->pdo->update("UPDATE Application 
                            SET version = :version, description = :description, updated = :updated
                            WHERE id = :id", 
                            array( 'version'=> $data[1], 'description'=> $data[2] , 'updated'=> 'NOW()','id' => $a->id ));
                    }
                }
            } 

            if(!$updated)
            {
                $apps = $this->pdo->insert("INSERT INTO Application( name, version, description, updated, server_id ) VALUES ( :name, :version, :description, :updated, :server_id )",
                            array( 'name'=> $data[0] , 'version'=> $data[1], 'description'=> $description , 'updated'=> 'NOW()', 'server_id' => $this->server ));

                $apps_name .= $data[0];
                echo "INSERT :: ". $data[0] ."\n";     
            }
        }

        if($apps_name != "")
        {
            $server = $this->pdo->query('SELECT * FROM Server WHERE id = '. $this->server .'');

            $this->pdo->update("UPDATE Server SET search = :search  WHERE id = :id", 
                            array( 'search'=> $server[0]->search ." ".$apps_name ,'id' => $this->server ));
        }
    }

    public function sendVirtualHost()
    {
        $server_content = $this->pdo->query('SELECT * FROM VirtualHost WHERE server_id = '. $this->server .'');

        if (is_dir( $this->folder )) 
        {
            if ($dh = opendir( $this->folder )) 
            {
                while (($file = readdir($dh)) !== false) 
                {
                    if( "." == $file || ".." == $file )
                    {
                        continue;
                    }

                    $content = $this->procOpen( 'cat '. $this->folder .'/'.$file );
                    $updated = false;

                    foreach ($server_content as $a)
                    {
                        if( $a->name == $file )
                        {
                            $updated = true;
                            echo "Updated :: ". $file ."\n";

                            $this->pdo->update("UPDATE VirtualHost SET content = :content WHERE id = :id", 
                                    array( 'content'=> $content, 'id' => $a->id )
                              );
                        }
                    }

                    if(!$updated)
                    {
                        $this->pdo->insert("INSERT INTO  VirtualHost ( name, content, server_id ) VALUES ( :name, :content, :server_id )", 
                                    array( 'content'=> $content, 'name' => $file , 'server_id' => $this->server )
                              );

                        echo "INSERT vHOST :: ". $file ."\n";     
                    }
                }
            }
        }
    }

    public function sendApacheErrorsLogs()
    {
		echo "Start Sync Apache \n";

    	exec('cat '.$this->apacheFolder, $output);
        
        $server_content = $this->pdo->query('SELECT apacheErrorLogsCount FROM Server WHERE id = '. $this->server .'');

        $length = count( $output );
        $start  = (empty( $server_content[0]->apacheErrorLogsCount  ) ) ? 0 : $server_content[0]->apacheErrorLogsCount ;
        
        for( $i = $start ; $i < $length ; $i++) 
        {
        	$line = $output[$i];

            preg_match('~^\[(.*?)\]~', $line, $date);
            
            if( empty( $date[1] ) ){ continue; }
            
            preg_match('~\] \[([a-z]*?)\] \[~', $line, $type);
            preg_match('~\] \[client ([0-9\.]*)\]~', $line, $client);
            preg_match('~\] (.*)$~', $line, $message);
            
            $type_send 		= (empty( $type[1] ) ) ? "" : $type[1];
            $client_send 	= (empty( $client[1] ) ) ? "" : $client[1];
            $message_send 	= (empty( $message[1] ) ) ? "" : $message[1];

            echo "Error Message :: ".$message_send."\n";
            
            $this->pdo->insert("INSERT INTO  ApacheLogs ( dateTime, type, client, message, server_id ) VALUES ( :dateTime, :type, :client, :message, :server_id )", 
                                    array( 'dateTime'=> $date[1], 'type' => $type_send , 'client' => $client_send, 'message' => $message_send , 'server_id' => $this->server )
                              );
        }

        $this->pdo->update("UPDATE Server SET apacheErrorLogsCount = :apacheErrorLogsCount  WHERE id = :id", 
                            array( 'apacheErrorLogsCount'=> $length ,'id' => $this->server ));
    }

    private function procOpen( $command )
	{
		$descriptorspec = array(

            0 => array("pipe", "r"), 
            1 => array("pipe", "w"),  
            2 => array("file", "/tmp/error-output.txt", "a") 

        );

        $cwd = '/tmp';
        $process = proc_open($command, $descriptorspec, $pipes, $cwd);

        if (is_resource($process))
        {   
            fclose($pipes[0]);

            $command_return = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            proc_close($process);
        }

        return $command_return;
	}
}


 ?>