<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\HistoryRepository")
 */
class History
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
     * @ORM\Column(name="fact", type="string", length=255)
     */
    private $fact;

    /**
    * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User",cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $createur;
        
    /**
     * @var string
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\Task", inversedBy="historys")
     * @ORM\JoinColumn(nullable=true)
     */
    private $task;

    public function __construct() {
        $this->created = new \DateTime;
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
     * Set fact
     *
     * @param string $fact
     * @return History
     */
    public function setFact($fact)
    {
        $this->fact = $fact;

        return $this;
    }

    /**
     * Get fact
     *
     * @return string 
     */
    public function getFact()
    {
        return $this->fact;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return History
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
     * Set createur
     *
     * @param \Algorithm\ManagerBundle\Entity\User $createur
     * @return History
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
     * Set task
     *
     * @param \Algorithm\ManagerBundle\Entity\Task $task
     * @return History
     */
    public function setTask(\Algorithm\ManagerBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Algorithm\ManagerBundle\Entity\History 
     */
    public function getTask()
    {
        return $this->task;
    }
}
