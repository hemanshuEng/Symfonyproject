<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({"like"="LikeNotification"})
 */
abstract class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;
    /**
     * @ORM\Column(type="boolean")
     */
    private $seen;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->seen = false;
    }



    

    /**
     * Get the value of user
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get the value of seen
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set the value of seen
     *
     * @return mixed $seen
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    }
}
