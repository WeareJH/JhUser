<?php

namespace JhUser\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use ZfcRbac\Identity\IdentityInterface;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use JsonSerializable;
use Doctrine\Common\Collections\Collection;
use Rbac\Role\RoleInterface;

/**
 * Class User
 * @package JhUser\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, IdentityInterface, JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true,  length=255, nullable=false)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255, nullable=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="display_name", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="state", nullable=true)
     */
    protected $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JhUser\Entity\HierarchicalRole")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * Initialise the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return \JhUser\Entity\User
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return \JhUser\Entity\User
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return \JhUser\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        if (!$this->displayName) {
            return null;
        } else {
            return ucwords($this->displayName);
        }
    }

    /**
     * @param string $displayName
     * @return \JhUser\Entity\User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = (string) $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return \JhUser\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int|null $state
     * @return \JhUser\Entity\User
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return \JhUser\Entity\User
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Set the list of roles
     * @param Collection $roles
     */
    public function setRoles(Collection $roles)
    {
        $this->roles->clear();
        foreach ($roles as $role) {
            $this->roles[] = $role;
        }
    }

    /**
     * Add one role to roles list
     * @param \Rbac\Role\RoleInterface $role
     */
    public function addRole(RoleInterface $role)
    {
        $this->roles[] = $role;
    }

    /**
     * Remove a particular role from a user
     *
     * @param RoleInterface $removeRole
     */
    public function removeRole(RoleInterface $removeRole)
    {
        foreach ($this->roles as $role) {
            if ($role->getName() === $removeRole->getName()) {
                $this->roles->removeElement($removeRole);
            }
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->displayName,
            'email' => $this->email,
            'state' => $this->state,
        ];
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtDate()
    {
        $this->setCreatedAt(new \DateTime);
    }
}
