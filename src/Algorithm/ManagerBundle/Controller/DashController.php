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

class DashController extends Controller
{
    public function indexAction()
    {
        $breadcrumb = array(
                
                      array( 'name' => 'Dashboard', 'route' => 'algorithm_wiki_homepage' )
            
                ); 
        
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:User")
                      ->getDashboardUsers();
        
        $clients = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Clients")
                      ->getDashboardClients();
        
        $servers = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Server")
                      ->getDashboardServers();
        
        $accounts = $this->getDoctrine()
                      ->getManager()
                      ->getRepository("AlgorithmManagerBundle:Account")
                      ->getDashboardAccounts();
        
        return $this->render('AlgorithmManagerBundle:Dashboard:index.html.twig', array(
            
            'breadcrumb_data' => $breadcrumb, 'users' => $users, 'clients' => $clients, 'servers' => $servers, 'accounts' => $accounts
            
        ));
    }
}
