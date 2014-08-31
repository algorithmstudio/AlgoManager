<?php

namespace Algorithm\ManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Settings
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="keyUser", type="string", length=8, nullable=true)
     */
    private $keyUser;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="sendMailNewTask", type="boolean", nullable=true)
     */
    private $sendMailNewTask;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="sendMailNewComment", type="boolean", nullable=true)
     */
    private $sendMailNewComment;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="sendMailUpdateTask", type="boolean", nullable=true)
     */
    private $sendMailUpdateTask;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="sendMailEndTask", type="boolean", nullable=true)
     */
    private $sendMailEndTask;


    public function __construct() {
        
        $this->sendMailNewTask      = false;
        $this->sendMailUpdateTask   = false;
        $this->sendMailNewComment   = false;
        $this->sendMailEndTask      = false;
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
     * Set email
     *
     * @param string $email
     * @return Settings
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Settings
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
     * Set key_user
     *
     * @param string $keyUser
     * @return Settings
     */
    public function setKeyUser($keyUser)
    {
        $this->keyUser = $keyUser;

        return $this;
    }

    /**
     * Get key_user
     *
     * @return string 
     */
    public function getKeyUser()
    {
        return $this->keyUser;
    }

    /**
     * Set sendMailNewTask
     *
     * @param boolean $sendMailNewTask
     * @return Settings
     */
    public function setSendMailNewTask($sendMailNewTask)
    {
        $this->sendMailNewTask = $sendMailNewTask;

        return $this;
    }

    /**
     * Get sendMailNewTask
     *
     * @return boolean 
     */
    public function getSendMailNewTask()
    {
        return $this->sendMailNewTask;
    }

    /**
     * Set sendMailNewComment
     *
     * @param boolean $sendMailNewComment
     * @return Settings
     */
    public function setSendMailNewComment($sendMailNewComment)
    {
        $this->sendMailNewComment = $sendMailNewComment;

        return $this;
    }

    /**
     * Get sendMailNewComment
     *
     * @return boolean 
     */
    public function getSendMailNewComment()
    {
        return $this->sendMailNewComment;
    }

    /**
     * Set sendMailUpdateTask
     *
     * @param boolean $sendMailUpdateTask
     * @return Settings
     */
    public function setSendMailUpdateTask($sendMailUpdateTask)
    {
        $this->sendMailUpdateTask = $sendMailUpdateTask;

        return $this;
    }

    /**
     * Get sendMailUpdateTask
     *
     * @return boolean 
     */
    public function getSendMailUpdateTask()
    {
        return $this->sendMailUpdateTask;
    }

    /**
     * Set sendMailEndTask
     *
     * @param boolean $sendMailEndTask
     * @return Settings
     */
    public function setSendMailEndTask($sendMailEndTask)
    {
        $this->sendMailEndTask = $sendMailEndTask;

        return $this;
    }

    /**
     * Get sendMailEndTask
     *
     * @return boolean 
     */
    public function getSendMailEndTask()
    {
        return $this->sendMailEndTask;
    }
}
