<?php

namespace Algorithm\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Algorithm\ManagerBundle\Entity\IP;

class SecurityController extends Controller
{
  public function loginAction()
  {
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
    if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('security.context')->getToken()->getUser()->setLastConnexion( new \DateTime );
        
        $ip_authorized =  $this->get('security.context')->getToken()->getUser()->getIps();
        
        $ip     = ( strpos($this->getRequest()->getClientIp(), '192') ) ? $this->getRequest()->getClientIp() : '82.229.96.9';
        $geoip  = $this->get('maxmind.geoip')->lookup($ip);
                        
        foreach ($ip_authorized as $i) 
        {
            if( $i->getIp() === $ip )
            {
                if( $i->getAuthorize() === false )
                {
                    throw $this->createNotFoundException('NOT FIND');
                }
                else
                {
                    $em->flush();        
                    
                    if ($this->get('security.context')->isGranted('ROLE_PROJECT') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) 
                    {
                        return $this->redirect($this->generateUrl('algorithm_wiki_projects'));
                    }
                    
                    return $this->redirect($this->generateUrl('algorithm_wiki_homepage'));   
                }
            }
        }        
        
        $ip_user = new IP();
        $ip_user->setIp($ip)
                ->setUser( $this->get('security.context')->getToken()->getUser() )
                ->setConnexion( new \DateTime() )
                ->setCountry( $geoip->getCountryName() )
                ->setCity( $geoip->getCity() )
                ;
        $em->persist($ip_user);

        $em->flush();
        
        if ($this->get('security.context')->isGranted('ROLE_PROJECT') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirect($this->generateUrl('algorithm_wiki_projects'));
        }
        
        return $this->redirect($this->generateUrl('algorithm_wiki_homepage'));    
    }
 
    $request = $this->getRequest();
    $session = $request->getSession();
 
    // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
    if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) 
    {
        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    } 
    else
    {
        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    }
 
    if($request->query->get('login'))
    {
        $login = $request->query->get('login');
        $password = $request->query->get('password');
    }
    else
    {
        $login = '';
        $password = '';
    }
            
    return $this->render('AlgorithmSecurityBundle:Security:login.html.twig', array(
      
      'last_username' => $session->get(SecurityContext::LAST_USERNAME),
      'error'         => $error,
      'login'         => $login,
      'password'      => $password
            
    ));
  }
  
  public function forgetAction()
  {      
      if($this->getRequest()->getMethod() == "POST")
      {
            $request = $this->getRequest(); 
            $mail = $this->getRequest()->request->get('mail');
            
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository("AlgorithmGestionBundle:Users")
                        ->findOneByMail($mail);
            
            if($user === null)
            {
                throw $this->createNotFoundException($mail);
            }
            
            $mdp = $this->generate();
                
            $user->setSalt(md5(time()));

            $factory = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $factory->encodePassword($mdp, $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('valide', "Votre mot de passe vous a été renvoyé");        
            return $this->redirect( $this->generateUrl('login') );
      }
      
      return $this->render('AlgorithmSecurityBundle:Security:forget.html.twig');
  }
  
    public function restartAction()
    {

    }
  
    public function generate() 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz;:!_-ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,10);
    }
}

?>

