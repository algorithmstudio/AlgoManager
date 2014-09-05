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
use JMS\SecurityExtraBundle\Annotation\Secure;
use Algorithm\ManagerBundle\Entity\Account;
use Algorithm\ManagerBundle\Form\AccountType;
use Algorithm\ManagerBundle\Form\ClientsType;
use Algorithm\ManagerBundle\Entity\Clients;

class ClientsController extends Controller
{
    public function indexAction()
    {
        $clients = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Clients")
                      ->getAll();
        
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.home.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'clients' => $clients
            
        ));
    }
    
    public function detailsAction($id)
    {
        $details = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Clients")
                      ->getDetails($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => $details->getName(), 'route' => 'algorithm_wiki_clients_details', 'id' => $details->getId() )
        );
            
        return $this->render('AlgorithmManagerBundle:Clients:clients.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'client' => $details
            
        ));        
    }
    
    public function accountAction($id)
    {
        $account = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Account")
                      ->find($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => $account->getClients()->getName(), 'route' => 'algorithm_wiki_clients_details', 'id' => $account->getClients()->getId() ),
                      array( 'name' => $account->getName(), 'route' => 'algorithm_wiki_clients_account_details', 'id' => $account->getId() )
        );
        
        $crypto  = $this->container->get('algorithm_wiki.crypto');
        $user    = $this->get('security.context')->getToken()->getUser();
        
        if($account->getClients()->getPrivate() == true)
        {
           $crypto->init( $user->getSettings()->getKeyUser() ); 
        }
        else
        {
            $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
        }
        
        $account->setPassword( $crypto->decrypt( $account->getPassword() ) );
                    
        return $this->render('AlgorithmManagerBundle:Clients:clients.account.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'account' => $account
            
        ));        
    }
    
    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function accountCreateAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => 'Création de compte', 'route' => 'algorithm_wiki_clients_account_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new AccountType(), new Account());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $account = $form->getData();
                
                $crypto  = $this->container->get('algorithm_wiki.crypto');
                $user    = $this->get('security.context')->getToken()->getUser();
                
                if($account->getClients()->getPrivate() == true)
                {
                   $crypto->init( $user->getSettings()->getKeyUser() ); 
                }
                else
                {
                    $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
                }
                
                $account->setPassword( $crypto->encrypt( $account->getPassword() ) );
                
                $account->setCreateur( $user );
                
                $account->setSearch(
                        
                        $account->getName(). " " .
                        $account->getClients()->getName()
                        
                    );
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($account);
                $em->flush();
                
                
                $this->get('session')->getFlashBag()->add('valide', "Le compte à été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_clients_details', array('id' => $account->getClients()->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.account.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("account", options={ "mapping": { "account_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function accountUpdateAction(Account $account)
    {   
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => $account->getClients()->getName(), 'route' => 'algorithm_wiki_clients_details', 'id' => $account->getClients()->getId() ),
                      array( 'name' => $account->getName(), 'route' => 'algorithm_wiki_clients_account_details', 'id' => $account->getId() ),
                      array( 'name' => "Édition", 'route' => 'algorithm_wiki_clients_account_details', 'id' => $account->getId() )
        );
        
        $request = $this->container->get('request');
        $crypto  = $this->container->get('algorithm_wiki.crypto');
        $user    = $this->get('security.context')->getToken()->getUser();
        
        if($account->getClients()->getPrivate() == true)
        {
           $crypto->init( $user->getSettings()->getKeyUser() ); 
        }
        else
        {
            $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
        }
        
        if($request->getMethod() == "GET")
        {
            $account->setPassword( $crypto->decrypt( $account->getPassword() ) );
        }
        
        $form = $this->createForm(new AccountType(), $account);
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $account = $form->getData();
                
                $account->setSearch(
                        
                        $account->getName(). " " .
                        $account->getClients()->getName()
                        
                    );

                $password = $crypto->encrypt( $account->getPassword() );
            
                $account->setPassword( $password );
                
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le compte à été modifié"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_clients_details', array('id' => $account->getClients()->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.account.edit.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), 'account' => $account
            
        )); 
    }
    
    /**
     * @ParamConverter("account", options={ "mapping": { "account_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function accountDeleteAction(Account $account)
    {   
        $client = $account->getClients()->getId();
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($account);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "Le compte a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_clients_details', array('id' => $client) ) );
    }
    
    public function clientsCreateAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => 'Créer un client', 'route' => 'algorithm_wiki_clients_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $user    = $this->get('security.context')->getToken()->getUser();
        
        if($user->getFirstConnexion()) 
        {
            $user->setFirstConnexion(false);               
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        
        $logger = $this->get('logger');
        $logger->info("INFO :::::::::::::::::::: ".$user->getFirstConnexion());
        
        $form = $this->createForm(new ClientsType(), new Clients());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $clients = $form->getData();
                
                $user = $this->get('security.context')->getToken()->getUser();                
                
                $clients->setSearch(
                        
                       $clients->getName()." ".
                       $clients->getActivity()." ".
                       $clients->getContact()." ".
                       $clients->getMail()
                        
                    )
                    ->setCreateur( $user );
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($clients);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le client à été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_clients_details', array('id' => $clients->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("client", options={ "mapping": { "client_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function clientsUpdateAction(Clients $client)
    {
        
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => $client->getName(), 'route' => 'algorithm_wiki_clients_details', 'id' => $client->getId() ),
                      array( 'name' => "Édition", 'route' => 'algorithm_wiki_clients_details', 'id' => $client->getId() )
            
        );
        
        $request = $this->container->get('request');
                
        $form = $this->createForm(new ClientsType(), $client);
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $clients = $form->getData();
                
                $clients->setSearch(
                        
                       $clients->getName()." ".
                       $clients->getActivity()." ".
                       $clients->getContact()." ".
                       $clients->getMail()
                        
                    );
            
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le client à été modifié"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_clients_details', array('id' => $clients->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.edit.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), "client" => $client
            
        )); 
    }
    
    /**
     * @ParamConverter("client", options={ "mapping": { "client_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function clientsDeleteAction(Clients $client)
    {   
        $em = $this->getDoctrine()->getManager();
        $em->remove($client);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "Le client a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_clients') );
    }
    
    /**
     * @ParamConverter("client", options={ "mapping": { "id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function clientsInfosAction(Clients $client)
    {           
        $breadcrumb = array(
                
                      array( 'name' => 'Clients', 'route' => 'algorithm_wiki_clients' ),
                      array( 'name' => $client->getName(), 'route' => 'algorithm_wiki_clients_details', 'id' => $client->getId() ),
                      array( 'name' => "Informations", 'route' => 'algorithm_wiki_clients_infos', 'id' => $client->getId() )
            
        );
        
        return $this->render('AlgorithmManagerBundle:Clients:clients.infos.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, "client" => $client
            
        )); 
    }
    
}
