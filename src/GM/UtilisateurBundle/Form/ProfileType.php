<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GM\UtilisateurBundle\Form;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
        ));
		
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
		->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),))
		->add('Sauvegarder', 'submit')
		;
    }
}
