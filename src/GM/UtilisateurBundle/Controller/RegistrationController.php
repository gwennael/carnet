<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GM\UtilisateurBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use GM\CarnetBundle\Entity\Carnet;
use GM\CarnetBundle\Entity\Liste;

use GM\UtilisateurBundle\Form\RegistrationType;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
		
		$carnet = new Carnet();
		$liste = new Liste();

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
		
		$class = get_class($user);
		$form = $this->createForm(new RegistrationType($class));

        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
			
			$user->setAdresse('');
			$user->setCodePostal(0);
			$user->setVille('');	
			$user->setTelephone(0);
			$user->setPortable(0);
			$user->setSite('');			
			$user->addRole('ROLE_USER');
			
			$carnet->setUsername($user->getUsername());
			$liste->setName($user->getUsername());
			
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);
			
			$eManager = $this->getDoctrine()->getManager();
			$eManager->persist($carnet);
			$eManager->persist($liste);
			$eManager->flush();

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('accueil');
                $response = new RedirectResponse($url);
            }
			
			$request->getSession()->getFlashBag()->add('notice', 'Enregistrement réussi ! Vous pouvez dès à présent utiliser l\'application.');

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
