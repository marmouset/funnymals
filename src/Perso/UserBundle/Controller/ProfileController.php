<?php
//ProjetPerso\src\Perso\UserBundle\Controller\ProfileController.php

namespace Perso\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Perso\UserBundle\Entity\User;
use Perso\GalerieBundle\Entity\Photo;
use Perso\GalerieBundle\Entity\Commentaire;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $url = $this->generateUrl('fos_user_profile_edit');
        return new RedirectResponse($url);
    }

    //permet de voir les détails d'un user
    public function showDetailsAction($page, User $userGet)
    {
        //on va récupérer les photos de ce user
        $em = $this->getDoctrine()->getManager();
        //$sesPhotos = $em->getRepository('PersoGalerieBundle:Photo')->findByUser($userGet->getId());
        $sesPhotos = $em->getRepository('PersoGalerieBundle:Photo')->getPhotosByUser(($this->getParameter('nb_img_by_page') + 1),$page, $userGet->getId());

        $sesCommentaires = $em->getRepository('PersoGalerieBundle:Commentaire')->findBy(array('user' => $userGet));

        //return $this->redirect($this->generateUrl('perso_user_view', array('slug' => $photoGet->getSlug())));
        return $this->render('FOSUserBundle:Profile:show_details_user.html.twig', array(
                'user'  => $userGet,
                'photos'=> $sesPhotos,
                'page'       => $page,
                'commentaires' => $sesCommentaires,
                'nombrePage' => ceil(count($sesPhotos)/($this->getParameter('nb_img_by_page') + 1))
        ));

        //return new Response("Hello World !");
    }


    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        //$form->get('imgUser')->setData('John');

        $form->handleRequest($request);


        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_edit');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }
        else {
            $this->container->get('logger')->info(
                sprintf('formulaire pas validé')
            );
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
