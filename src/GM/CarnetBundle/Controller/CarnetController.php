<?php

namespace GM\CarnetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use GM\CarnetBundle\Form\ListeType;
use GM\CarnetBundle\Form\CarnetType;
use GM\CarnetBundle\Entity\Carnet;
use GM\CarnetBundle\Entity\Liste;
use GM\CarnetBundle\Entity\CarnetListe;
use GM\CarnetBundle\Entity\CarnetRepository;
use GM\CarnetBundle\Entity\ListeRepository;

class CarnetController extends Controller
{  
	public function accueilAction()
	{
		return $this->render('GMCarnetBundle:Carnet:accueil.html.twig');
	}
	
	public function indexAction()
	{
		return $this->render('GMCarnetBundle:Carnet:index.html.twig');
	}
	
	public function listeAction()
	{		
		$eManager = $this->getDoctrine()->getManager();
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($this->getUser()->getId());
		$idCarnet = $eManager->getRepository('GMCarnetBundle:Carnet')->getIdCarnet($utilisateur->getUsername());
		
		$listeCarnetObjet = $eManager->getRepository('GMCarnetBundle:CarnetListe')->findByCarnet($idCarnet);
		// var_dump($listeCarnetObjet);
		
		$listeCarnet = array();
		for ($i = 0; $i <sizeof($listeCarnetObjet); $i++) {
			$listeCarnet[$i] = $listeCarnetObjet[$i]->getListe()->getName();
		}

		// var_dump($listeCarnet);
		
		usort($listeCarnet, "strcasecmp");
		
		// var_dump($listeCarnet);
		
		return $this->render('GMCarnetBundle:Carnet:carnet.html.twig', array(
			'listeCarnet'  => $listeCarnet,));
	}
  
	public function ajouterAction(Request $request)
	{
		$eManager = $this->getDoctrine()->getManager();
		
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($this->getUser()->getId());
		$name=$utilisateur->getUsername();
		
		$id = $eManager->getRepository('GMCarnetBundle:Carnet')->getIdCarnet($name);
		$carnet = $eManager->getRepository('GMCarnetBundle:Carnet')->find($id);
		
		$formulaire = $this->createForm(new ListeType());
		
		if($formulaire->handleRequest($request)->isValid()){
			
			$username = $formulaire->get('name')->getData();
			$idListe = $eManager->getRepository('GMCarnetBundle:Liste')->getIdListe($username);
			$liste = $eManager->getRepository('GMCarnetBundle:Liste')->find($idListe);
			
			if (null === $liste) {
				throw new NotFoundHttpException("L'utilisateur ".$username." n'existe pas.");
			}
			
			$carnetListe = new CarnetListe();
			
			$carnetListe->setCarnet($carnet);
			$carnetListe->setListe($liste);
			
			$nomCarnet = $carnet->getUsername();
			$nomListe = $liste->getName();
			$nomCarnetListe = ''.$nomCarnet.$nomListe;
			
			$verifPresenceBdd = $eManager->getRepository('GMCarnetBundle:CarnetListe')->verificationCarnetListe($nomCarnetListe);
			
			if($nomCarnetListe == $nomCarnet.$nomCarnet){
				$request->getSession()->getFlashBag()->add('fail', 'Vous ne pouvez pas vous ajouter !');
			
				return $this->redirect($this->generateUrl('gm_carnet_ajouter'));
			}
			
			if($verifPresenceBdd != null){
				$request->getSession()->getFlashBag()->add('fail', 'Vous ne pouvez pas ajouter 2 fois un même contact !');
			
				return $this->redirect($this->generateUrl('gm_carnet_ajouter'));
			}			
			
			$carnetListe->setNom($nomCarnetListe);
			
			$eManager->persist($carnetListe);
			$eManager->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Contact ajouté !');
			
			return $this->redirect($this->generateUrl('gm_carnet_ajouter'));
		}
		
		return $this->render('GMCarnetBundle:Carnet:ajouter.html.twig', array(
			'formulaire' => $formulaire->createView(),
			));
	}
	
	public function detailAction($nom)
	{
		$eManager = $this->getDoctrine()->getManager();
		
		$idUtilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->getIdUtilisateur($nom);
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($idUtilisateur);

		if (null === $utilisateur) {
		  throw new NotFoundHttpException("L'utilisateur d'id ".$idUtilisateur." n'existe pas.");
		}
		
		return $this->render('GMCarnetBundle:Carnet:detail.html.twig', array(
		  'utilisateur' => $utilisateur
		));
	}
	
	public function listeSupprimerAction()
	{
		$eManager = $this->getDoctrine()->getManager();
		
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($this->getUser()->getId());
		
		$idCarnet = $eManager->getRepository('GMCarnetBundle:Carnet')->getIdCarnet($utilisateur->getUsername());
		$listeCarnetObjet = $eManager->getRepository('GMCarnetBundle:CarnetListe')->findByCarnet($idCarnet);		
		
		$listeCarnet = array();
		
		for ($i = 0; $i <sizeof($listeCarnetObjet); $i++) {
			$listeCarnet[$i] = $listeCarnetObjet[$i]->getListe()->getName();
		}
		
		usort($listeCarnet, "strcasecmp");
		
		return $this->render('GMCarnetBundle:Carnet:supprimer.html.twig', array(
			'listeCarnet'  => $listeCarnet,));
	}
	
	public function confirmSupprimerAction()
	{		
		$suppression = null;
		
		if (isset($_POST['suppression']))
		{
			$suppression = $_POST['suppression'];
			// var_dump($suppression);
		}
		
		return $this->render('GMCarnetBundle:Carnet:confirm.html.twig', array(
			'suppression'  => $suppression,));
	}
	
	public function supprimerAction(Request $request)
	{
		$suppression = $_GET['suppression'];
		$eManager = $this->getDoctrine()->getManager();		
		$utilisateur = $eManager->getRepository('GMUtilisateurBundle:Utilisateur')->find($this->getUser()->getId());
		$nomUtilisateur = $utilisateur->getUsername();
		
		for($i=0;$i<sizeof($suppression);$i++){
			$contactSup = ''.$nomUtilisateur.$suppression[$i];
			echo $contactSup;
			
			$eManager->getRepository('GMCarnetBundle:CarnetListe')->supprimerCarnetListe($contactSup);

		}
		
		$request->getSession()->getFlashBag()->add('notice', 'Contact(s) supprimé(s) !');
		
		return $this->redirect($this->generateUrl('gm_carnet_liste'));
	}

}