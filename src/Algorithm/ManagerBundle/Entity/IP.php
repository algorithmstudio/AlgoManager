<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IP
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Algorithm\ManagerBundle\Entity\IPRepository")
 */
class IP
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
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var boolean
     *
     * @ORM\Column(name="authorize", type="boolean")
     */
    private $authorize;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Algorithm\ManagerBundle\Entity\User", inversedBy="ips")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="connexion", type="datetime", nullable=true)
     */
    private $connexion;


    public function __construct()
    {
        $this->authorize = true;
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
     * Set authorize
     *
     * @param boolean $authorize
     * @return IP
     */
    public function setAuthorize($authorize)
    {
        $this->authorize = $authorize;

        return $this;
    }

    /**
     * Get authorize
     *
     * @return boolean 
     */
    public function getAuthorize()
    {
        return $this->authorize;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return IP
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return IP
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return IP
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
     * Set connexion
     *
     * @param \DateTime $connexion
     * @return IP
     */
    public function setConnexion($connexion)
    {
        $this->connexion = $connexion;

        return $this;
    }

    /**
     * Get connexion
     *
     * @return \DateTime 
     */
    public function getConnexion()
    {
        return $this->connexion;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return IP
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
}
