<?php 

/**
* Gestion de la base de données
*/
class PDOClass
{
	private $db;

	private $host;
	private $dbName;
	private $login;
	private $mdp;

	function __construct($host, $login, $mdp)
	{
		$this->host 	= $host;
		$this->dbName 	= 'algo_wiki';
		$this->login 	= $login;
		$this->mdp 		= $mdp;

		$this->productMode();
	}

	private function productMode()
	{
		try
		{
			$this->db = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName,
								$this->login, $this->mdp,
								array(
									PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
									PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING 
								));
		}
		catch(PDOException $e)
		{
			echo "Impossible de se connecter à la DB";
			die();
		}
	}

	public function query($sql, $data=array())
	{
		$requete = $this->db->prepare($sql);
		$requete->execute($data);
		
		return $requete->fetchAll(PDO::FETCH_OBJ);
	}

	public function insert($sql, $data=array())
	{
		$requete = $this->db->prepare($sql);
		$requete->execute($data);
		
		return $this->db->lastInsertId();
	}

	public function delete($sql, $data=array())
	{
		$requete = $this->db->prepare($sql);
		$requete->execute($data);
		
		return;
	}

	public function update($sql, $data=array())
	{
		$requete = $this->db->prepare($sql);
		$requete->execute($data);
		
		return;
	}
}

?>

