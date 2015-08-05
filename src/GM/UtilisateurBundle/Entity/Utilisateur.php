<?php

namespace GM\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Utilisateur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GM\UtilisateurBundle\Entity\UtilisateurRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Utilisateur extends BaseUser
{	
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
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
     * Set adresse
     *
     * @param string $adresse
     *
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
     * Set codePostal
     *
     * @param integer $codePostal
     *
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
     *
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
     *
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
     *
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

    /**
     * Set site
     *
     * @param string $site
     *
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
}
