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

/**
 * Description of ProjectController
 *
 * @author Nicolas Passaquet <nicolas.passaquet@gmail.com>
 */
namespace Algorithm\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Algorithm\ManagerBundle\Form\ProjectType;
use Algorithm\ManagerBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Algorithm\ManagerBundle\Form\TaskType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Algorithm\ManagerBundle\Entity\Task;
use Algorithm\ManagerBundle\Entity\Comment;
use Algorithm\ManagerBundle\Form\CommentType;
use Algorithm\ManagerBundle\Entity\History;

class ProjectController extends Controller
{
    public function indexAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' )
            
                ); 
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();             
        
        $projects = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Project")
                      ->getAll();
                
        return $this->render('AlgorithmManagerBundle:Project:projects.home.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'projects' => $projects
            
        ));
    }
    
    public function projectCreateAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => 'Créer un projet', 'route' => 'algorithm_wiki_projects_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new ProjectType(), new Project());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $project = $form->getData();
                
                $user = $this->get('security.context')->getToken()->getUser();    
                $user->setLastConnexionDelta( new \DateTime );
                
                $project->setCreateur( $user )
                        ->setLastUpdate( new \DateTime );
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le projet à été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_projects_details', array('id' => $project->getId() ) ) );
            }
            
        }
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();   
        
        return $this->render('AlgorithmManagerBundle:Project:projects.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("task", options={ "mapping": { "id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function projectUpdateAction(Project $project)
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => $project->getName(), 'route' => 'algorithm_wiki_projects_details', 'id' => $project->getId() ),
                      array( 'name' => 'Edition', 'route' => 'algorithm_wiki_projects_update','id' => $project->getId() )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new ProjectType(), $project );
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {                   
                $project = $form->getData();               
                
                $project->setLastUpdate( new \DateTime );
                
                $user = $this->get('security.context')->getToken()->getUser(); 
                $user->setLastConnexionDelta( new \DateTime );
                            
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "Le projet à été mis à jour"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_projects_details', array('id' => $project->getId() ) ) );
            }
            
        }
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();   
        
        return $this->render('AlgorithmManagerBundle:Project:projects.update.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), 'project' => $project
            
        )); 
    }    
    
    public function detailsAction($id)
    {
        $details = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Project")
                      ->getDetails($id);
        
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => $details->getName(), 'route' => 'algorithm_wiki_projects_details', 'id' => $details->getId() )
        );
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();  
            
        return $this->render('AlgorithmManagerBundle:Project:projects.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'project' => $details
            
        ));        
    }
    
    public function taskCreateAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => 'Créer une tâche', 'route' => 'algorithm_wiki_tasks_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new TaskType(), new Task());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $task = $form->getData();
                
                $user = $this->get('security.context')->getToken()->getUser();  
                $user->setLastConnexionDelta( new \DateTime );
                
                $history = new History();
                $history->setFact("a créer la tâche" )
                        ->setCreateur( $user )
                        ->setTask($task);
                
                $task->setCreateur( $user )
                     ->setLastUpdate( new \DateTime )
                     ->setStatut("Nouveau")
                     ->setCompleted(0);
                
                $task->getProject()->setLastUpdate( new \DateTime );
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->persist($history);
                $em->flush();
                
                $sendMail = $task->getUser()->getSettings()->getSendMailNewTask();
                
                if($sendMail)
                { 
                    $content = $this->renderView('AlgorithmManagerBundle:Project:mail.html.twig', 
                        
                        array( 'nameTask' => $task->getName(),
                               'nameProject' => $task->getProject()->getName(),
                               'taskDate' => $task->getCreated(),
                               'taskCreateur' => $task->getCreateur()->getFirstname() . " " . $task->getCreateur()->getName(),
                               'taskDescription' => $task->getDescription()
                        
                        ));
                    
                    $message = \Swift_Message::newInstance()
                            ->setSubject('['. $task->getPriority() .'] Une nouvelle tâche vous a été assigné')
                            ->setFrom('noreply@algorithmstudio.fr')
                            ->setTo($task->getUser()->getEmail())
                            ->setBody( $content, 'text/html');

                    $this->get('mailer')->send($message);
                }
                
                $this->get('session')->getFlashBag()->add('valide', "La tâche a été créé"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_tasks_details', array('id' => $task->getId() ) ) );
            }
        }
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();  
        
        return $this->render('AlgorithmManagerBundle:Project:tasks.create.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView()
            
        )); 
    }
    
    /**
     * @ParamConverter("task", options={ "mapping": { "id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function taskUpdateAction(Task $task)
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => 'Créer une tâche', 'route' => 'algorithm_wiki_tasks_create' )
            
        );
        
        $request = $this->container->get('request');
        
        $form = $this->createForm(new TaskType(), $task);
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            {   
                $task = $form->getData();
                
                $task->setStatut("Mis à jour");
                
                $user = $this->get('security.context')->getToken()->getUser(); 
                $user->setLastConnexionDelta( new \DateTime );
                
                $history = new History();
                $history->setFact('a mis à jour la tâche ' . $task->getName()  )
                        ->setCreateur( $user )
                        ->setTask($task);
                
                $task->getProject()->setLastUpdate( new \DateTime );
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($history);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('valide', "La tâche a été modifié"); 
                return $this->redirect( $this->generateUrl('algorithm_wiki_tasks_details', array('id' => $task->getId() ) ) );
            }
            
        }
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();  
        
        return $this->render('AlgorithmManagerBundle:Project:tasks.update.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'form' => $form->createView(), 'task' => $task
            
        )); 
    }
    
    public function taskDetailsAction($id)
    {
        $details = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Task")
                      ->getDetails($id);
                
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => $details->getProject()->getName(), 'route' => 'algorithm_wiki_projects_details', 'id' => $details->getProject()->getId() ),
                      array( 'name' => $details->getName(), 'route' => 'algorithm_wiki_tasks_details', 'id' => $details->getId() )
        );
        
        $request = $this->container->get('request');
                
        $form = $this->createForm(new CommentType(), new Comment());
        
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            
            if($form->isValid())
            { 
                $comment = $form->getData();
                
                $user = $this->get('security.context')->getToken()->getUser(); 
                $user->setLastConnexionDelta( new \DateTime );
                
                $history = new History();
                $history->setFact("a ajouté un commentaire" )
                        ->setCreateur( $user )
                        ->setTask($details);
                                
                $comment->setUser($user)
                        ->setDateWrite( new \DateTime )
                        ->setTask( $details );
                                
                $details->getProject()->setLastUpdate( new \DateTime );
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->persist($history);
                $em->flush();
                
                $sendMail       = $details->getCreateur()->getSettings()->getSendMailNewComment();
                $sendMailUser   = $details->getUser()->getSettings()->getSendMailNewComment();
            
                if($sendMail || $sendMailUser)
                { 
                    $mail = array();
                    
                    if( $sendMail ) { $mail[] = $details->getCreateur()->getEmail(); }
                    if( $sendMailUser ) { $mail[] = $details->getUser()->getEmail(); }
                    
                    $content = $this->renderView('AlgorithmManagerBundle:Project:mail.html.twig', 

                        array( 'nameTask' => $details->getName(),
                               'nameProject' => $details->getProject()->getName(),
                               'taskDate' => $details->getLastUpdate(),
                               'taskCreateur' => $details->getCreateur()->getFirstname() . " " . $details->getCreateur()->getName(),
                               'taskDescription' => $comment->getMessage() . " ajouté par " . $comment->getUser()->getFirstname() ." ".$comment->getUser()->getName()

                        ));

                    $message = \Swift_Message::newInstance()
                            ->setSubject('Un commentaire a été ajouté à une tâche')
                            ->setFrom('noreply@algorithmstudio.fr')
                            ->setTo( $mail )
                            ->setBody( $content, 'text/html');

                    $this->get('mailer')->send($message);
                }
                
                $this->get('session')->getFlashBag()->add('valide', "Le commentaire a été ajouté"); 
            }
        }
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();  
        
        $comments = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Comment")
                      ->getByTask($id);
            
        return $this->render('AlgorithmManagerBundle:Project:tasks.details.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'task' => $details, 'form' => $form->createView(), 'comments' => $comments 
            
        ));        
    }
    
    public function taskHistoryAction($id)
    {
        $details = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Task")
                      ->getDetails($id);
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->flush();
                
        $breadcrumb = array(
                
                      array( 'name' => 'Projets', 'route' => 'algorithm_wiki_projects' ),
                      array( 'name' => $details->getProject()->getName(), 'route' => 'algorithm_wiki_projects_details', 'id' => $details->getProject()->getId() ),
                      array( 'name' => $details->getName(), 'route' => 'algorithm_wiki_tasks_details', 'id' => $details->getId() ),
                      array( 'name' => "Historique", 'route' => 'algorithm_wiki_tasks_details', 'id' => $details->getId() )
        );
        
        return $this->render('AlgorithmManagerBundle:Project:tasks.history.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'task' => $details
            
        ));        
    }
    
    /**
     * @ParamConverter("task", options={ "mapping": { "task_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function taskEndAction(Task $task)
    {   
        $task->setStatut('Terminé')
             ->setLastUpdate( new \DateTime );        
        
        $user = $this->get('security.context')->getToken()->getUser(); 
                
        $history = new History();
        $history->setFact("a cloturé la tâche" )
                ->setCreateur( $user )
                ->setTask($task);
        
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($history);
        $em->flush();
        
        $sendMail = $task->getUser()->getSettings()->getSendMailEndTask();
                
        if($sendMail)
        { 
            $content = $this->renderView('AlgorithmManagerBundle:Project:mail.html.twig', 

                array( 'nameTask' => $task->getName(),
                       'nameProject' => $task->getProject()->getName(),
                       'taskDate' => $task->getCreated(),
                       'taskCreateur' => $task->getCreateur()->getFirstname() . " " . $task->getCreateur()->getName(),
                       'taskDescription' => ""

                ));

            $message = \Swift_Message::newInstance()
                    ->setSubject('Une tâche a été clôturé')
                    ->setFrom('noreply@algorithmstudio.fr')
                    ->setTo($task->getUser()->getEmail())
                    ->setBody( $content, 'text/html');

            $this->get('mailer')->send($message);
        }
        
        $this->get('session')->getFlashBag()->add('valide', "La tâche a été clôturé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_tasks_details', array('id' => $task->getId()) ) );
    }
    
    /**
     * @ParamConverter("task", options={ "mapping": { "task_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function taskDeleteAction(Task $task)
    {   
        $project = $task->getProject()->getId();
        
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "La tâche a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_projects_details', array('id' => $project) ) );
    }
    
    /**
     * @ParamConverter("task", options={ "mapping": { "task_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function taskAchievementAction(Task $task)
    {
        $request = $this->container->get('request');
                
        if($request->getMethod() == "POST")
        {
            $achievement = $request->request->get('achievement');
            $timeSpend = $request->request->get('timeSpend');
            $task->setCompleted($achievement)
                 ->setTimeSpend($timeSpend)
                 ->setLastUpdate( new \DateTime );
            
            $user = $this->get('security.context')->getToken()->getUser(); 
            $user->setLastConnexionDelta( new \DateTime );
                
            $history = new History();
            $history->setFact("a mis à jour la progession de la tâche" )
                    ->setCreateur( $user )
                    ->setTask($task);
            
            $task->getProject()->setLastUpdate( new \DateTime );
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
            $em->flush();
            
            $sendMail = $task->getCreateur()->getSettings()->getSendMailUpdateTask();
            
            if($sendMail)
            { 
                $content = $this->renderView('AlgorithmManagerBundle:Project:mail.html.twig', 

                    array( 'nameTask' => $task->getName(),
                           'nameProject' => $task->getProject()->getName(),
                           'taskDate' => $task->getLastUpdate(),
                           'taskCreateur' => $task->getCreateur()->getFirstname() . " " . $task->getCreateur()->getName(),
                           'taskDescription' => 'La tâche est passée à '.$achievement."%"

                    ));

                $message = \Swift_Message::newInstance()
                        ->setSubject('La tâche a été mis à jour')
                        ->setFrom('noreply@algorithmstudio.fr')
                        ->setTo($task->getCreateur()->getEmail())
                        ->setBody( $content, 'text/html');

                $this->get('mailer')->send($message);
            }
            
            $this->get('session')->getFlashBag()->add('valide', "La progression a été mis à jour");
            return $this->redirect( $this->generateUrl('algorithm_wiki_tasks_details', array('id'=>$task->getId()) ) );
        }
        
        $this->get('session')->getFlashBag()->add('error', "Une erreur s'est produite");
        return $this->redirect( $this->generateUrl('algorithm_wiki_tasks_details', array('id'=>$task->getId()) ) );
    }
    
    /**
     * @ParamConverter("project", options={ "mapping": { "project_id" : "id" } })
     * @Secure(roles="ROLE_ADMIN")
     */
    public function projectDeleteAction(Project $project)
    {           
        $user = $this->get('security.context')->getToken()->getUser(); 
        $user->setLastConnexionDelta( new \DateTime );
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('valide', "Le projet a été supprimé");
        return $this->redirect( $this->generateUrl('algorithm_wiki_projects' ) );
    }
}
