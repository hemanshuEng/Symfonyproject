<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Serializable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",message="This e-mail is already used")
 * @UniqueEntity(fields="username",message="usename is already used")
 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50,unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5,max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8,max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=4,max=50)
     */
    private $fullname;
    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost",mappedBy="user")
     */
    private $posts;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User",mappedBy="following")
     */
    private $followers;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User",inversedBy="followers")
     * @ORM\JoinTable(name="following",joinColumns={
     *     @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * },
     *    inverseJoinColumns={@ORM\JoinColumn(name="following_user_id",referencedColumnName="id")}
     * 
     * )
     * 
     */
    private $following;
   /**
    * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost",mappedBy="likedBy")
    */
    private $postsLiked;
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
    }
    public function getRoles()
    {
        return $this->roles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function getSalt()
    {
        return null;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,

        ) = unserialize($serialized);
    }
    public function eraseCredentials()
    { }
    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set the value of roles
     *
     * @param  array  $roles
     *
     * @return  self
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of followers
     * @return Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Get 
     * @return Collection
     */
    public function getFollowing()
    {
        return $this->following;
    }
    public function follow(User $userToFollow)
    {
        if ($this->getFollowing()->contains($userToFollow)) {
            return;
        }
        $this->getFollowing()->add($userToFollow);
    }

    /**
     * Get the value of postsLiked
     * @return Collection
     */ 
    public function getPostsLiked()
    {
        return $this->postsLiked;
    }
}
