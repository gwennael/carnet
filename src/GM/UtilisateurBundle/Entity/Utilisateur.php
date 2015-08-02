<?php

namespace GM\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Utilisateur
 *
 * @ORM\Table()
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur existe déjà !")
 * @ORM\Entity(repositoryClass="GM\UtilisateurBundle\Entity\UtilisateurRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Utilisateur implements UserInterface
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
	 * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage="Le nom d'utilisateur doit faire au moins {{ limit }} caractères !")
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
	 * @Assert\NotBlank()
     * @Assert\length(min=5, minMessage="Le mot de passe doit faire au moins {{ limit }} caractères !")
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
	 * @Assert\NotBlank()
	 * @Assert\Email(checkMX="true")
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;
	
	/**
     * @var string
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;
	
	/**
     * @var integer
     * @Assert\length(max=5, exactMessage="Le code postale est composé de {{ limit }} chiffres !")
     * @ORM\Column(name="codePostal", type="integer")
     */
    private $codePostal;
	
	/**
     * @var string
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;
	
	/**
     * @var integer
	 * @Assert\length(max=10, maxMessage="Le numéro de téléphonene dépasse pas les {{ limit }} chiffres !")
     * @ORM\Column(name="telephone", type="integer")
     */
    private $telephone;
	
	/**
     * @var integer
	 * @Assert\length(max=10, maxMessage="Le numéro de téléphonene dépasse pas les {{ limit }} chiffres !")
     * @ORM\Column(name="portable", type="integer")
     */
    private $portable;
	
	/**
     * @var string
     * @Assert\Url()
     * @ORM\Column(name="site", type="string", length=255)
     */
    private $site;

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
     * Set username
     *
     * @param string $username
     * @return Utilisateur
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Utilisateur
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
     * Set salt
     *
     * @param string $salt
     * @return Utilisateur
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Utilisateur
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
     * Set roles
     *
     * @param array $roles
     * @return Utilisateur
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }
	
	public function eraseCredentials()
    {
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Utilisateur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return Utilisateur
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     * @return Utilisateur
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Utilisateur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     * @return Utilisateur
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return integer 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set portable
     *
     * @param integer $portable
     * @return Utilisateur
     */
    public function setPortable($portable)
    {
        $this->portable = $portable;

        return $this;
    }

    /**
     * Get portable
     *
     * @return integer 
     */
    public function getPortable()
    {
        return $this->portable;
    }
}
