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
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Algorithm\ManagerBundle\Form\SettingsType;
use Algorithm\ManagerBundle\Entity\IP;

class SettingsController extends Controller
{
    public function indexAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Paramètres', 'route' => 'algorithm_wiki_settings' )
            
                ); 
        
        
        $ips = $this->getDoctrine()
                    ->getManager()
                    ->getRepository("AlgorithmManagerBundle:IP")
                    ->findAll();        
        
        return $this->render('AlgorithmManagerBundle:Settings:index.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'ips' => $ips
            
        ));
    }
    
    /**
     * @ParamConverter("ip", options={ "mapping": { "id" : "id" } })
     */
    public function updateIPAction(IP $ip)
    {
        if($ip->getAuthorize())
        {
            $ip->setAuthorize(false);
            $this->get('session')->getFlashBag()->add('valide', "L'IP : ". $ip->getIp() ." a été révoqué");
        }
        else
        {
            $ip->setAuthorize(true);
            $this->get('session')->getFlashBag()->add('valide', "L'IP : ". $ip->getIp() ." a été autorisé");
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        
        
        return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings' ) );
    }
    
    public function updatePasswordAction()
    {
        $request = $this->getRequest();
        
        if($request->getMethod() == "POST")
        {
            $admin = $this->get('security.context')->getToken()->getUser();
            $factory = new MessageDigestPasswordEncoder('sha512', true, 10);
    
            $password = $factory->encodePassword($request->request->get('password'), $admin->getSalt());
            
            if($password != $admin->getPassword())
            {
                $this->get('session')->getFlashBag()->add('error', "Mot de passe érroné"); 
                return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings' ) );
            }

            $new_password = $factory->encodePassword($request->request->get('new_password'), $admin->getSalt());
            $confirm_password = $factory->encodePassword($request->request->get('confirm_password'), $admin->getSalt());
            
            if($new_password != $confirm_password )
            {
                $this->get('session')->getFlashBag()->add('error', "Vos nouveaux mot de passe ne correspondent pas"); 
                return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings' ) );
            }
            
            $admin->setPassword($new_password);
            
            $em = $this->getDoctrine()->getManager();    
            $em->flush();

            $this->get('session')->getFlashBag()->add('valide', "Votre mot de passe à été modifié");
            return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings' ) );
        }
        
        return $this->render('AlgorithmCommerceBundle:Account:update.password.html.twig' );
    }
    
    public function mailAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Paramètres', 'route' => 'algorithm_wiki_settings' ),
                      array( 'name' => 'Mail', 'route' => 'algorithm_wiki_settings_mail' )
            
                ); 
        
        $logger = $this->get('logger');
        
        $request    = $this->getRequest();
        $crypto     = $this->container->get('algorithm_wiki.crypto');
        $crypto->init( $this->container->getParameter('algorithm_wiki.key') );
        
        $user       = $this->get('security.context')->getToken()->getUser();
        $settings   = $user->getSettings();
        $settings->setPassword( $crypto->decrypt( $settings->getPassword() ) );       
        
        $old_email      = $settings->getEmail();
        $old_password   = $settings->getPassword();
        
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $form = $this->createForm(new SettingsType(), $settings );
        }
        else
        {
            $form = $this->createForm(new \Algorithm\ManagerBundle\Form\SettingsUserType(), $settings );
        }
                
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $settings = $form->getData();  
                
                if( $settings->getEmail() !== null  )
                {        
                    if( !preg_match('#gmail.com#', $settings->getEmail() ) )
                    {                        
                        $this->get('session')->getFlashBag()->add('error', "Votre email n'est pas adresse gmail");
                        return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings_mail' ) ); 
                    }
                    else
                    {
                        if( null === $settings->getPassword() )
                        {
                            $this->get('session')->getFlashBag()->add('error', "Vous devez renseigner le mot de passe de votre mail");
                            return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings_mail' ) ); 
                        }
                    }
                }
                               
                $file = '../app/config/parameters_test.yml';
                $file_content = file_get_contents($file);
                
                if( preg_match('/|MAIL_USER|/', $file_content) === 1 )
                {
                    $file_content = str_replace('|MAIL_USER|', $settings->getEmail(), $file_content);
                    $file_content = str_replace('|MAIL_PASSWORD|', $settings->getPassword() , $file_content); 
                }
                else
                {
                    $logger->info('REFRESH');
                    
                    $file_content = str_replace( $old_email, $settings->getEmail(), $file_content);
                    $file_content = str_replace( $old_password, $settings->getPassword() , $file_content); 
                    
                }
                
                $logger->info('MATCH :: ' . preg_match('#|MAIL_USER|#', $file_content));
                
                file_put_contents($file, $file_content);
                
                
                $settings->setPassword( $crypto->encrypt( $settings->getPassword() ) );
                $user->setSettings( $settings );
                
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Paramètre mis à jour");
                return $this->redirect( $this->generateUrl( 'algorithm_wiki_settings_mail' ) );
            }
        }
        
        return $this->render('AlgorithmManagerBundle:Settings:mail.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        ));
    }
}
