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

class SearchController extends Controller
{
    
    public function indexAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Search', 'route' => 'algorithm_wiki_homepage' )
            
                ); 
        
        $request = $this->getRequest();
        
        if($request->getMethod() == "POST")
        {
            $search = $request->request->get('search');
            
            if($search == "*") $search = "";
            
            if($search == "ip")
            {
                $servers = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:Server")
                          ->findAll();
                
                return $this->render('AlgorithmManagerBundle:Tables:servers.html.twig', array(
            
                    'breadcrumb_data' => $breadcrumb, 'servers' => $servers

                ));
            }
            
            $users = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:User")
                          ->getSearchUsers($search);

            $clients = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:Clients")
                          ->getSearchClients($search);

            $servers = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:Server")
                          ->getSearchServers($search);

            $accounts = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:Account")
                          ->getSearchAccounts($search);
            
            $applications = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:Application")
                          ->getSearchApps($search);
            
            $vhosts = $this->getDoctrine()
                          ->getManager()
                          ->getRepository("AlgorithmManagerBundle:VirtualHost")
                          ->getSearchHost($search);
        }
        
        return $this->render('AlgorithmManagerBundle:Search:index.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'users' => $users, 'clients' => $clients, 'servers' => $servers, 'accounts' => $accounts, 'apps' => $applications, 'vhost' =>$vhosts
            
        ));
    }
}
