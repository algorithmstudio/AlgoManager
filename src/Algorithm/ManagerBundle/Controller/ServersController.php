<?php

/*
 * Copyright (C) 2014 Nicolas Passaquet <nicolas.passaquet@gmail.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Algorithm\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Algorithm\ManagerBundle\Entity\Server;
use Symfony\Component\HttpFoundation\Response;
use Algorithm\ManagerBundle\Form\ServerType;
use DateTime;

class ServersController extends Controller
{    
    public function indexAction()
    {
        $serveurs = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Server")
                      ->findAll();
        
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.home.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'servers' => $serveurs
            
        ));
    }
    
    public function detailsAction($id)
    {
        $server = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Server")
                      ->getDetails($id);
        
        $logs = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:ApacheLogs")
                      ->getApacheLogs($server);
        
        $crypto  = $this->container->get('algorithm_wiki.crypto');
        $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
        $server->setPassword( $crypto->decrypt( $server->getPassword() ) );
        
        //$this->parseApacheLogs($server);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => $server->getName(), 'route' => 'algorithm_wiki_servers_details', "id" => $server->getId() )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'server' => $server, 'logs' => $logs
            
        ));
    }
    
    public function createAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => "Ajouter un serveur", 'route' => 'algorithm_wiki_servers_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new ServerType(), new Server());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $server = $form->getData();   
                
                $crypto  = $this->container->get('algorithm_wiki.crypto');
                $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
                $server->setPassword( $crypto->encrypt( $server->getPassword() ) );
                
                $server->setCreateur( $this->get('security.context')->getToken()->getUser() );
                
                $server->setSearch(
                    
                        $server->getName()." ".
                        $server->getIp()." ".
                        $server->getHebergeur()." ".
                        $server->getBandePassante()." ".
                        $server->getProcesseur()." ".
                        $server->getMemoire()
                        
                    );
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($server);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le serveur à été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_servers_details', array('id' => $server->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("server", options={ "mapping": { "id" : "id" } })
     */
    public function updateAction(Server $server)
    {   
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => $server->getName(), 'route' => 'algorithm_wiki_servers_details', "id" => $server->getId() ),
                      array( 'name' => "Édition", 'route' => 'algorithm_wiki_servers_update', "id" => $server->getId() )
        );
        
        $crypto  = $this->container->get('algorithm_wiki.crypto');
        $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
        $server->setPassword( $crypto->decrypt( $server->getPassword() ) );
        
        $request = $this->container->get('request');
        $form = $this->createForm(new ServerType(), $server);
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $server = $form->getData();
                
                $crypto  = $this->container->get('algorithm_wiki.crypto');
                $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
                $server->setPassword( $crypto->encrypt( $server->getPassword() ) );
                
                $server->setSearch(
                    
                        $server->getName()." ".
                        $server->getIp()." ".
                        $server->getHebergeur()." ".
                        $server->getBandePassante()." ".
                        $server->getProcesseur()." ".
                        $server->getMemoire()
                        
                    );
                
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le serveur à été modifié"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_servers_details', array('id' => $server->getId() ) ) );
            }
        }
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.edits.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), 'server' => $server
            
        )); 
    }
    
    /**
     * @ParamConverter("server", options={ "mapping": { "server_id" : "id" } })
     */
    public function deleteAction(Server $server)
    {           
        $em = $this->getDoctrine()->getManager();
        $em->remove($server);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "Le serveur a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_servers') );
    }
    
    public function appsAction($id)
    {
        $server = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Server")
                      ->getDetails($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => $server->getName(), 'route' => 'algorithm_wiki_servers_details', "id" => $server->getId() ),
                      array( 'name' => "Applications", 'route' => 'algorithm_wiki_servers_apps', "id" => $server->getId() )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.apps.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'server' => $server
            
        ));
    }
    
    public function virtualHostDetailsAction($id)
    {
        $vhost = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:VirtualHost")
                      ->find($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => $vhost->getServer()->getName(), 'route' => 'algorithm_wiki_servers_details', "id" => $vhost->getServer()->getId() ),
                      array( 'name' => "VirtualHosts", 'route' => 'algorithm_wiki_servers_details', "id" => $vhost->getServer()->getId() ),
                      array( 'name' => $vhost->getName(), 'route' => 'algorithm_wiki_servers_virtualhost_details', "id" => $vhost->getId() ),
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Servers:servers.vhost.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'vhost' => $vhost
            
        ));
    }
    
    /**
     * @ParamConverter("server", options={ "mapping": { "id" : "id" } })
     */ 
    public function synchronisationAction(Server $server)
    {
        $request = $this->container->get('request');
        
        $breadcrumb = array(
                
                      array( 'name' => 'Serveurs', 'route' => 'algorithm_wiki_servers' ),
                      array( 'name' => $server->getName(), 'route' => 'algorithm_wiki_servers_details', "id" => $server->getId() ),
                      array( 'name' => "Synchronisation", 'route' => 'algorithm_wiki_servers_synchro_file', "id" => $server->getId() )
            
                );
        
        if($request->getMethod() == "POST")
        {
            $folderVhost   = $request->request->get('folder-site');
            $synchApps     = $request->request->get('sync-apps');
            $synchVhosts   = $request->request->get('sync-vhosts');
            $folderApache  = $request->request->get('folder-errors');
            $syncApache    = $request->request->get('sync-errors');
            
            $file          = "algo_sync_src/sync.server.". $server->getId() .".php";
                        
            $content = $this->renderView('AlgorithmManagerBundle:Servers:synchronisation.file.html.twig', array(
            
                'folder' => $folderVhost,
                'synchApps' => $synchApps, 
                'synchVhosts' => $synchVhosts, 
                'server_id' => $server->getId(),
                'syncApache' => $syncApache,
                'folder_apache' => $folderApache,
                'ip_server' => $request->request->get('ip-sql'),
                'sql_user' => $request->request->get('sql-user'),
                'sql_password' => $request->request->get('sql-password')

            ));
            
            file_put_contents($file, $content);
            
            exec( "zip -R algo_sync/sync_".$server->getId()." algo_sync_src/pdo.class.php algo_sync_src/synchro.server.class.php ". $file );
            
            return $this->render('AlgorithmManagerBundle:Servers:servers.tutorial.html.twig', array(
            
                'breadcrumb_data' => $breadcrumb, 'server' => $server, 'file' => "algo_sync/sync_". $server->getId(). ".zip"

            ));
        }
        
        return $this->render('AlgorithmManagerBundle:Servers:synchronisation.settings.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'server' => $server
            
        ));
    }
    
    /**
     * @ParamConverter("server", options={ "mapping": { "id" : "id" } })
     */ 
    public function pingAction(Server $server)
    {
        $request = $this->getRequest();
        
        if( $request->isXmlHttpRequest() )
        {
            $response = $this->pingParseAction($server, true);
            
            return new Response( $response );
        }
    }
    
    private function pingParseAction(Server $server, $json = false)
    {
        exec('ping -c1 '. $server->getIp(). '', $ping);
            
        $time = explode("time=", $ping[1]);

        if(preg_match('/100/', $ping[4] ) )
        {
            $respond = true;
        }
        else
        {
            $respond = false;
        }

        if($json) $response = json_encode( array( $time[1], $respond ) );
        else $response = array( $time[1], $respond );
        
        return $response;
    }
}
