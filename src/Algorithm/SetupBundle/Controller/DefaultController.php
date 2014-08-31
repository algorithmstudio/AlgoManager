<?php

namespace Algorithm\SetupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Algorithm\ManagerBundle\Entity\User;
use Algorithm\ManagerBundle\Form\UserAdminType;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Algorithm\ManagerBundle\Entity\Settings;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AlgorithmSetupBundle:Default:index.html.twig');
    }
    
    public function secondStepAction()
    {
        $request = $this->getRequest();
        
        $form = $this->createForm(new UserAdminType(), new User() );
        
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
                $confirm_password = $factory->encodePassword($request->request->get('confirm_password'), $user->getSalt());
                
                if($password !== $confirm_password)
                {
                    $this->get('session')->getFlashBag()->add('error', "Vos nouveaux mot de passe ne correspondent pas"); 
                    return $this->redirect( $this->generateUrl( 'algorithm_setup_two' ) ); 
                }
                
                $user->setPassword($password)
                     ->setUsername( $user->getFirstname() )
                     ->setSettings( $settings );
                
                $user->setRoles( array('ROLE_SUPER_ADMIN') );
                $user->setSearch( $user->getFirstname() ." ". $user->getName() );
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                
                return $this->redirect( $this->generateUrl( 'algorithm_wiki_homepage' ) );
            }
        }
        
        return $this->render('AlgorithmSetupBundle:Default:second.step.html.twig', array( 'form' => $form->createView() ));
    }
    
    public function firstStepAction()
    {
        $request = $this->getRequest();
        
        if($request->getMethod() == "POST")
        {
            /*if( !isset( $request->request->get('host') ) )
            {
                $this->get('session')->getFlashBag()->add('error', "Le host est obligatoire"); 
                return $this->redirect( $this->generateUrl( 'algorithm_setup_one' ) ); 
            }
            
            if( !isset($request->request->get('user')))
            {
                $this->get('session')->getFlashBag()->add('error', "L'utilisateur est obligatoire"); 
                return $this->redirect( $this->generateUrl( 'algorithm_setup_one' ) ); 
            }
            
            if( !isset($request->request->get('password')))
            {
                $this->get('session')->getFlashBag()->add('error', "Le mot de passe est obligatoire"); 
                return $this->redirect( $this->generateUrl( 'algorithm_setup_one' ) ); 
            }*/
            
            $file = '../app/config/parameters_test.yml';
            $file_content = file_get_contents($file);
                        
            $file_content = str_replace('|HOST|', $request->request->get('host') , $file_content);
            $file_content = str_replace('|USER|', $request->request->get('user') , $file_content);
            $file_content = str_replace('|PASSWORD|', $request->request->get('password') , $file_content);
            
            file_put_contents($file, $file_content);
                        
            $command = "php ../app/console doctrine:database:create";

            $descriptorspec = array(
                0 => array("pipe", "r"),  // // stdin est un pipe où le processus va lire
                1 => array("pipe", "w"),  // stdout est un pipe où le processus va écrire
                2 => array("file", "/tmp/error-output.txt", "a") // stderr est un fichier
             );

            $process = proc_open($command, $descriptorspec, $pipes );

            if (is_resource($process)) 
            {
                fclose($pipes[1]);
                $return_value = proc_close($process);
            }
            
            exec('php ../app/console doctrine:schema:update --dump-sql');
            exec('php ../app/console doctrine:schema:update --force');
            
            $this->get('session')->getFlashBag()->add('valide', "La base de donnée a été créé"); 
            return $this->redirect( $this->generateUrl( 'algorithm_setup_two' ) ); 
        }
        
        return $this->render('AlgorithmSetupBundle:Default:first.step.html.twig');
    }
    
    private function generate() 
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,8);
    }
}
