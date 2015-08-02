<?php

namespace GM\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use GM\UtilisateurBundle\Entity\Utilisateur;


class UtilisateurEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
		->remove('username')
		->remove('password')
		->remove('Enregistrer')
		->add('adresse', 'text', array('required' => false))
		->add('codePostal', 'number', array('required' => false))
		->add('ville', 'text', array('required' => false))
		->add('telephone', 'number', array('required' => false, 'max_length'=>10))
		->add('portable', 'number', array('required' => false, 'max_length'=>10))
		->add('site', 'text', array('required' => false))
		->add('Sauvegarder', 'submit')
		;
    }
  
    public function getName()
    {
      return 'gm_utilisateurbundle_utilisateur_edit';
    }
	
	public function getParent()
    {
      return new UtilisateurType();
    }
}
