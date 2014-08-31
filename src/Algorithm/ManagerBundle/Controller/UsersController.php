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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Algorithm\ManagerBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Algorithm\ManagerBundle\Form\UserType;
use Algorithm\ManagerBundle\Form\UserUpdateType;
use Algorithm\ManagerBundle\Entity\Settings;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UsersController extends Controller
{    
    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:User")
                      ->findAll();
        
        $breadcrumb = array(
                
                      array( 'name' => 'Utilisateurs', 'route' => 'algorithm_wiki_users' )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Users:users.home.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'users' => $users
            
        ));
    }
    
    public function detailsAction($id)
    {
        $user = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:User")
                      ->find($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Utilisateurs', 'route' => 'algorithm_wiki_users' ),
                      array( 'name' => $user->getName()." ".$user->getFirstname(), 'route' => 'algorithm_wiki_users_details', "id" => $user->getId() )
            
                ); 
        
        return $this->render('AlgorithmManagerBundle:Users:users.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'user' => $user
            
        ));
    }
    
    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function createAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Utilisateurs', 'route' => 'algorithm_wiki_users' ),
                      array( 'name' => 'Création d\'un utilisateur', 'route' => 'algorithm_wiki_users_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new UserType(), new User());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $user = $form->getData();                
                $user->setSalt(md5(time()));
                
                $settings = new Settings();
                $settings->setKeyUser( $this->generate() );

                $factory = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $factory->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password)
                     ->setUsername( $user->getFirstname() )
                     ->setSettings( $settings );
                
                $user->setSearch( $user->getFirstname() ." ". $user->getName() );
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                
                $this->get('session')->getFlashBag()->add('valide', "L'utilisateur à été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_users_details', array('id' => $user->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Users:users.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("user", options={ "mapping": { "id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(User $user)
    {   
        $breadcrumb = array(
                
                      array( 'name' => 'Utilisateurs', 'route' => 'algorithm_wiki_users' ),
                      array( 'name' => $user->getFirstname()." ".$user->getName(), 'route' => 'algorithm_wiki_users_details', "id" => $user->getId() ),
                      array( 'name' => "Édition", 'route' => 'algorithm_wiki_users_update', "id" => $user->getId() )
        );
        
        $request = $this->container->get('request');
        $form = $this->createForm(new UserUpdateType(), $user);
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $user = $form->getData();
                
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "L'utilisateur à été modifié"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_users_details', array('id' => $user->getId() ) ) );
            }
            
        }
        
        return $this->render('AlgorithmManagerBundle:Users:users.edit.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), 'user' => $user
            
        )); 
    }
    
    /**
     * @ParamConverter("user", options={ "mapping": { "user_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(User $user)
    {           
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "L'utilisateur a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_users') );
    }
    
    private function generate() 
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,8);
    }
}
