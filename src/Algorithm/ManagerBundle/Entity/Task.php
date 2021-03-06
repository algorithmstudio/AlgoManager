<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\TaskRepository")
 */
class Task
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
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;
    
    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;
    
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\Project", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;
    
    /**
    * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User",cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $createur;
    
    /**
    * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User",cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="completed", type="integer")
     */
    private $completed;
    
    /**
     * @var string
     *
     * @ORM\Column(name="timeEstimate", type="integer", nullable=true)
     */
    private $timeEstimate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="timeSpend", type="integer", nullable=true)
     */
    private $timeSpend;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="deadLine", type="datetime", nullable=true)
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
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\Comment", mappedBy="task", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $comments;
    
    /**     
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\History", mappedBy="task", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $historys;
    

    public function __construct() {
        $this->created  = new \DateTime;
        $this->timeEstimate = 0;
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection;
        $this->historys = new \Doctrine\Common\Collections\ArrayCollection;
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
     * @return Task
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
     * Set statut
     *
     * @param string $statut
     * @return Task
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set completed
     *
     * @param integer $completed
     * @return Task
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return integer 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set deadLine
     *
     * @param \DateTime $deadLine
     * @return Task
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
     * @return Task
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
     * @return Task
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
     * Set project
     *
     * @param \Algorithm\ManagerBundle\Entity\Project $project
     * @return Task
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

    /**
     * Set createur
     *
     * @param \Algorithm\ManagerBundle\Entity\User $createur
     * @return Task
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

    /**
     * Set user
     *
     * @param \Algorithm\ManagerBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\Algorithm\ManagerBundle\Entity\User $user = null)
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
     * Set timeEstimate
     *
     * @param integer $timeEstimate
     * @return Task
     */
    public function setTimeEstimate($timeEstimate)
    {
        $this->timeEstimate = $timeEstimate;

        return $this;
    }

    /**
     * Get timeEstimate
     *
     * @return integer 
     */
    public function getTimeEstimate()
    {
        return $this->timeEstimate;
    }

    /**
     * Set timeSpend
     *
     * @param integer $timeSpend
     * @return Task
     */
    public function setTimeSpend($timeSpend)
    {
        $this->timeSpend = $timeSpend;

        return $this;
    }

    /**
     * Get timeSpend
     *
     * @return integer 
     */
    public function getTimeSpend()
    {
        return $this->timeSpend;
    }

    /**
     * Add comments
     *
     * @param \Algorithm\ManagerBundle\Entity\Comment $comments
     * @return Task
     */
    public function addComment(\Algorithm\ManagerBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Algorithm\ManagerBundle\Entity\Comment $comments
     */
    public function removeComment(\Algorithm\ManagerBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add historys
     *
     * @param \Algorithm\ManagerBundle\Entity\History $historys
     * @return Task
     */
    public function addHistory(\Algorithm\ManagerBundle\Entity\History $historys)
    {
        $this->historys[] = $historys;

        return $this;
    }

    /**
     * Remove historys
     *
     * @param \Algorithm\ManagerBundle\Entity\History $historys
     */
    public function removeHistory(\Algorithm\ManagerBundle\Entity\History $historys)
    {
        $this->historys->removeElement($historys);
    }

    /**
     * Get historys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistorys()
    {
        return $this->historys;
    }
}
