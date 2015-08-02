<?php

namespace GM\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bridge\Twig\AppVariable;

use GM\UtilisateurBundle\Entity\Utilisateur;
use GM\CarnetBundle\Entity\Carnet;
use GM\CarnetBundle\Entity\Liste;
use GM\UtilisateurBundle\Form\UtilisateurType;
use GM\UtilisateurBundle\Form\UtilisateurEditType;

class UtilisateurController extends Controller
{
	public function loginAction(Request $request)
	{
		
		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return $this->redirectToRoute('accueil');
		}
		
		$authenticationUtils = $this->get('security.authentication_utils');

		return $this->render('GMUtilisateurBundle:Utilisateur:login.html.twig', array(
			'last_username' => $authenticationUtils->getLastUsername(),
			'error'         => $authenticationUtils->getLastAuthenticationError(),
		));
	}
  
	public function inscriptionAction(Request $request)
	{
		$factory = $this->get('security.encoder_factory');
		$utilisateur = new Utilisateur();
		$carnet = new Carnet();
		$liste = new Liste();
		$encoder = $factory->getEncoder($utilisateur);
		$formulaire = $this->createForm(new UtilisateurType(), $utilisateur);
		
		if($formulaire->handleRequest($request)->isValid()){
			
			$password = $encoder->encodePassword($utilisateur->getPassword(), $utilisateur->getSalt());
			$utilisateur->setPassword($password);
			
			$utilisateur->setAdresse('');
			$utilisateur->setCodePostal(0);
			$utilisateur->setVille('');	
			$utilisateur->setTelephone(0);
			$utilisateur->setPortable(0);
			$utilisateur->setSite('');			
			
			if($utilisateur->getUsername()=='admin'){
				$utilisateur->setRoles(array('ROLE_ADMIN'));
			}
			else{
				$utilisateur->setRoles(array('ROLE_USER'));
			}
			
			$utilisateur->setSalt('');
			
			$carnet->setUsername($utilisateur->getUsername());
			
			$liste->setName($utilisateur->getUsername());
			
			$eManager = $this->getDoctrine()->getManager();
			$eManager->persist($utilisateur);
			$eManager->persist($carnet);
			$eManager->persist($liste);
			$eManager->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Enregistrement de l\'utilisateur réussi');
			
			return $this->redirect($this->generateUrl('accueil'));
		}
		
		return $this->render('GMUtilisateurBundle:Utilisateur:inscription.html.twig', array('formulaire' => $formulaire->createView()));
	}
	
	public function profilAction(Request $request)
	{
		$id = $this->getUser()->getId();
		$eManager = $this->getDoctrine()->getManager();
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($id);

		if (null === $utilisateur) {
		  throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
		}
		
		return $this->render('GMUtilisateurBundle:Utilisateur:profil.html.twig', array(
		  'utilisateur' => $utilisateur
		));
	}
	
	public function editProfilAction(Request $request)
	{
		$id = $this->getUser()->getId();
		$eManager = $this->getDoctrine()->getManager();
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($id);
		$formulaire = $this->createForm(new UtilisateurEditType(), $utilisateur);

		if ($formulaire->handleRequest($request)->isValid()) {
		  // Inutile de persister ici, Doctrine connait déjà notre annonce
		  $eManager->flush();

		  $request->getSession()->getFlashBag()->add('notice', 'Profil utilisateur modifié.');

		  return $this->redirect($this->generateUrl('gm_utilisateur_profil'));
		}

		return $this->render('GMUtilisateurBundle:Utilisateur:editProfil.html.twig', array(
		  'formulaire'   => $formulaire->createView(),
		  'utilisateur' => $utilisateur // Je passe également l'annonce à la vue si jamais elle veut l'afficher
		));
	}
}
