<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\ClientsRepository")
 */
class Clients
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
     * @ORM\Column(name="activity", type="string", length=255, nullable=true)
     */
    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="informations", type="text", nullable=true)
     */
    private $informations;
    
    /**     
     * @ORM\OneToMany(targetEntity="Algorithm\ManagerBundle\Entity\Account", mappedBy="clients", cascade={"persist", "remove"} )
     * @ORM\JoinColumn(nullable=true)
     */
    private $accounts;
    
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
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection;
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
     * @return Clients
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
     * Set activity
     *
     * @param string $activity
     * @return Clients
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Clients
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Clients
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set informations
     *
     * @param string $informations
     * @return Clients
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
     * Add accounts
     *
     * @param \Algorithm\ManagerBundle\Entity\Account $accounts
     * @return Clients
     */
    public function addAccount(\Algorithm\ManagerBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;

        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \Algorithm\ManagerBundle\Entity\Account $accounts
     */
    public function removeAccount(\Algorithm\ManagerBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Clients
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
     * @return Clients
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
     * Set search
     *
     * @param string $search
     * @return Clients
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
     * @return Account
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
}
