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
 * Description of ProjectUser
 *
 * @author Nicolas Passaquet <nicolas.passaquet@gmail.com>
 */
namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\ProjectUserRepository")
 */
class ProjectUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User")
     */
     private $user;

     /**
      * @ORM\Id
      * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\Project")
      */
     private $project;

    /**
     * Set user
     *
     * @param \Algorithm\ManagerBundle\Entity\User $user
     * @return ProjectUser
     */
    public function setUser(\Algorithm\ManagerBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Algorithm\ManagerBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \Algorithm\ManagerBundle\Entity\Project $project
     * @return ProjectUser
     */
    public function setProject(\Algorithm\ManagerBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Algorithm\ManagerBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}
