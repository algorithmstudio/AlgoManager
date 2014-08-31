<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\ProjectRepository")
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", length=255, nullable=true)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="informations", type="text", nullable=true)
     */
    private $informations;
    
    /**     
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\Task", mappedBy="project", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $tasks;
    
    /**
    * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User",cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $createur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="deadLine", type="date", nullable=true)
     */
    private $deadLine;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lastUpdate", type="datetime")
     */
    private $lastUpdate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @var string
     *
     * @ORM\Column(name="search", type="text", nullable=true)
     */
    private $search;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;
    
    
    public function __construct() {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection;
        $this->created = new \DateTime;
        $this->private = false;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return Project
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set informations
     *
     * @param string $informations
     * @return Project
     */
    public function setInformations($informations)
    {
        $this->informations = $informations;

        return $this;
    }

    /**
     * Get informations
     *
     * @return string 
     */
    public function getInformations()
    {
        return $this->informations;
    }

    /**
     * Set deadLine
     *
     * @param \DateTime $deadLine
     * @return Project
     */
    public function setDeadLine($deadLine)
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    /**
     * Get deadLine
     *
     * @return \DateTime 
     */
    public function getDeadLine()
    {
        return $this->deadLine;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Project
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime 
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Project
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set search
     *
     * @param string $search
     * @return Project
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return string 
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return Project
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Add tasks
     *
     * @param \Algorithm\ManagerBundle\Entity\Task $tasks
     * @return Project
     */
    public function addTask(\Algorithm\ManagerBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Algorithm\ManagerBundle\Entity\Task $tasks
     */
    public function removeTask(\Algorithm\ManagerBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set createur
     *
     * @param \Algorithm\ManagerBundle\Entity\User $createur
     * @return Project
     */
    public function setCreateur(\Algorithm\ManagerBundle\Entity\User $createur = null)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return \Algorithm\ManagerBundle\Entity\User 
     */
    public function getCreateur()
    {
        return $this->createur;
    }
}
