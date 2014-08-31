<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="user", type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="hebergeur", type="string", length=255, nullable=true)
     */
    private $hebergeur;

    /**
     * @var string
     *
     * @ORM\Column(name="memoire", type="string", length=255, nullable=true)
     */
    private $memoire;

    /**
     * @var string
     *
     * @ORM\Column(name="bandePassante", type="string", length=255, nullable=true)
     */
    private $bandePassante;

    /**
     * @var string
     *
     * @ORM\Column(name="processeur", type="string", length=255, nullable=true)
     */
    private $processeur;

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
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\Application", mappedBy="server", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $applications;
    
    /**     
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\ApacheLogs", mappedBy="server", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $apacheErrorLogs;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="apacheErrorLogsCount", type="integer", nullable=true)
     */
    private $apacheErrorLogsCount;
    
    /**
     * @var string
     *
     * @ORM\Column(name="search", type="text", nullable=true)
     */
    private $search;
    
    /**     
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\VirtualHost", mappedBy="server", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $virtualhost;
    
    private $hasPing;
    private $pingTime;


    public function __construct() 
    {
        $this->created          = new \DateTime;
        $this->applications     = new \Doctrine\Common\Collections\ArrayCollection;
        $this->apacheErrorLogs  = new \Doctrine\Common\Collections\ArrayCollection;
        $this->virtualhost      = new \Doctrine\Common\Collections\ArrayCollection;
        
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
     * @return Server
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
     * Set user
     *
     * @param string $user
     * @return Server
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Server
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set virtualhost
     *
     * @param string $virtualhost
     * @return Server
     */
    public function setVirtualhost($virtualhost)
    {
        $this->virtualhost = $virtualhost;

        return $this;
    }

    /**
     * Get virtualhost
     *
     * @return string 
     */
    public function getVirtualhost()
    {
        return $this->virtualhost;
    }

    
    /**
     * Set ip
     *
     * @param string $ip
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set hebergeur
     *
     * @param string $hebergeur
     * @return Server
     */
    public function setHebergeur($hebergeur)
    {
        $this->hebergeur = $hebergeur;

        return $this;
    }

    /**
     * Get hebergeur
     *
     * @return string 
     */
    public function getHebergeur()
    {
        return $this->hebergeur;
    }

    /**
     * Set memoire
     *
     * @param string $memoire
     * @return Server
     */
    public function setMemoire($memoire)
    {
        $this->memoire = $memoire;

        return $this;
    }

    /**
     * Get memoire
     *
     * @return string 
     */
    public function getMemoire()
    {
        return $this->memoire;
    }

    /**
     * Set bandePassante
     *
     * @param string $bandePassante
     * @return Server
     */
    public function setBandePassante($bandePassante)
    {
        $this->bandePassante = $bandePassante;

        return $this;
    }

    /**
     * Get bandePassante
     *
     * @return string 
     */
    public function getBandePassante()
    {
        return $this->bandePassante;
    }

    /**
     * Set processeur
     *
     * @param string $processeur
     * @return Server
     */
    public function setProcesseur($processeur)
    {
        $this->processeur = $processeur;

        return $this;
    }

    /**
     * Get processeur
     *
     * @return string 
     */
    public function getProcesseur()
    {
        return $this->processeur;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return Server
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return string 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Server
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
     * Add applications
     *
     * @param \Algorithm\ManagerBundle\Entity\Application $applications
     * @return Server
     */
    public function addApplication(\Algorithm\ManagerBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;

        return $this;
    }

    /**
     * Remove applications
     *
     * @param \Algorithm\ManagerBundle\Entity\Application $applications
     */
    public function removeApplication(\Algorithm\ManagerBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add virtualhost
     *
     * @param \Algorithm\ManagerBundle\Entity\VirtualHost $virtualhost
     * @return Server
     */
    public function addVirtualhost(\Algorithm\ManagerBundle\Entity\VirtualHost $virtualhost)
    {
        $this->virtualhost[] = $virtualhost;

        return $this;
    }

    /**
     * Remove virtualhost
     *
     * @param \Algorithm\ManagerBundle\Entity\VirtualHost $virtualhost
     */
    public function removeVirtualhost(\Algorithm\ManagerBundle\Entity\VirtualHost $virtualhost)
    {
        $this->virtualhost->removeElement($virtualhost);
    }
    
    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVirtualhosts()
    {
        return $this->virtualhost;
    }

    /**
     * Set search
     *
     * @param string $search
     * @return Server
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
     * Add apacheErrorLogs
     *
     * @param \Algorithm\ManagerBundle\Entity\Apachelogs $apacheErrorLogs
     * @return Server
     */
    public function addApacheErrorLog(\Algorithm\ManagerBundle\Entity\ApacheLogs $apacheErrorLogs)
    {
        $this->apacheErrorLogs[] = $apacheErrorLogs;

        return $this;
    }

    /**
     * Remove apacheErrorLogs
     *
     * @param \Algorithm\ManagerBundle\Entity\Apachelogs $apacheErrorLogs
     */
    public function removeApacheErrorLog(\Algorithm\ManagerBundle\Entity\ApacheLogs $apacheErrorLogs)
    {
        $this->apacheErrorLogs->removeElement($apacheErrorLogs);
    }

    /**
     * Get apacheErrorLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApacheErrorLogs()
    {
        return $this->apacheErrorLogs;
    }

    /**
     * Set apacheErrorLogsCount
     *
     * @param integer $apacheErrorLogsCount
     * @return Server
     */
    public function setApacheErrorLogsCount($apacheErrorLogsCount)
    {
        $this->apacheErrorLogsCount = $apacheErrorLogsCount;

        return $this;
    }

    /**
     * Get apacheErrorLogsCount
     *
     * @return integer 
     */
    public function getApacheErrorLogsCount()
    {
        return $this->apacheErrorLogsCount;
    }
    
    public function ping()
    {
        if($this->ip == null) return;
        if($this->hasPing != null) return;
        
        exec('ping -c1 '. $this->ip. '', $ping);
        
        $this->pingTime = explode("time=", $ping[1]);

        if(preg_match('/100/', $ping[4] ) )
        {
            $this->hasPing = false;
        }
        else
        {
            $this->hasPing = true;
        }
        
        
        return $this->hasPing;
        
    }
    
    public function pingTime()
    {
        return $this->pingTime;
    }
}
