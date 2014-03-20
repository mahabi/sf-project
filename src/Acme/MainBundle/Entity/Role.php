<?php
 
namespace Acme\MainBundle\Entity;
 
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
 
/**
 * Acme\MainBundle\Entity\Role
 * 
 * @ORM\Entity
 * @ORM\Table(name="sf_role")
 */
class Role implements RoleInterface, \Serializable
{
    /**
     * @var integer $id
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
     * @var string $name
     * 
     * @ORM\Column(name="name", type="string", length=20, unique=true)
     */
    private $name;
    
    /**
     * Users in group (Inverse Side)
     * 
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;
    
    public function __construct()
    {
    	$this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getRole()
    {
    	return $this->getName();
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
     * @return Role
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
     * Add users
     *
     * @param \Acme\MainBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Acme\MainBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Acme\MainBundle\Entity\User $users
     */
    public function removeUser(\Acme\MainBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    /**
     * Serializes the content of the current User object
     * @return string
     */
    public function serialize()
    {
    	/*
    	 * ! Don't serialize $users field !
    	*/
    	return \serialize(array(
    			$this->id,
    			$this->name
    	));
    }
    
    /**
     * Unserializes the given string in the current Role object
     * @param serialized
     */
    public function unserialize($serialized)
    {
    	list(
    			$this->id,
    			$this->name
    	) = \unserialize($serialized);
    }
}