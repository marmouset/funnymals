<?php

namespace Perso\GalerieBundle\Controller;

use Perso\GalerieBundle\Entity\Photo;
use Perso\GalerieBundle\Entity\VoteUserPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Perso\GalerieBundle\Form\PhotoType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class GalerieController extends Controller
{
    public function indexAction($page)
    {
        //return $this->render('PersoGalerieBundle:Galerie:index.html.twig', array('name' => $name));
        $em = $this->getDoctrine()->getManager();
        //$photos = $em->getRepository('PersoGalerieBundle:Photo')->findAll();
        $photos = $em->getRepository('PersoGalerieBundle:Photo')->getAllPhotosDesc(7, $page);

        return $this->render('PersoGalerieBundle:Galerie:index.html.twig', array('photos' => $photos,
            'page'       => $page,
            'nombrePage' => ceil(count($photos)/7)));
    }

    public function voirAction(Photo $photoGet)
    {
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository('PersoGalerieBundle:Photo')->find($photoGet->getId());
        return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo));
    }

    public function voteDownAction(Photo $photoGet)
    {
        $em = $this->getDoctrine()->getManager();

        //dans un premier temps on va vérifier si le user courant n'a pas déjà voté pour cette photo
        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $VoteUserPhotos = $em->getRepository("PersoGalerieBundle:VoteUserPhoto")->existVoteUser($photoGet);

        $existeVote = false;
        foreach($VoteUserPhotos as $VoteUserPhoto) {
             //$VoteUserPhoto->getPhoto()->getLegende();
            $existeVote = true;
        }

        if(!$existeVote)
        {
            //on va insérer le vote dans VoteUserPhoto
            $newVote = new VoteUserPhoto();
            $newVote->setPhoto($photoGet);
            $newVote->setUser($this->getUser());
            $newVote->setDate(new \Datetime());
            $em->persist($newVote);

            //ensuite on fait l'update sur Photo et l'insertion dans VoteUserPhoto
            $recupnbDown = $photoGet->getNbDown();
            $photoGet->setNbDown($recupnbDown + 1);
            $em->persist($photoGet);

            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Votre vote a bien été pris en compte');
        }
        else
        {
            $this->get('session')->getFlashBag()->add('info', 'Vous avez déjà enregistré un vote pour cette photo');
        }


        return $this->redirect($this->generateUrl('perso_galerie_homepage'));

    }

    public function voteUpAction(Photo $photoGet)
    {
        //dans un premier temps on va vérifier si le user courant n'a pas déjà voté pour cette photo
        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        //ensuite on fait l'update
        $em = $this->getDoctrine()->getManager();
        $recupnbUp = $photoGet->getNbUp();
        $photoGet->setNbUp($recupnbUp + 1);
        $em->persist($photoGet);
        $em->flush();

        return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photoGet));
    }

    public function addPhotoAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException('Accès limité aux auteurs.');
        }

        $photo = new Photo;
        $form = $this->createForm(new PhotoType, $photo);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // Ici : On traite manuellement le fichier uploadé
                $photo->setUser($this->getUser());

                $em = $this->getDoctrine()->getManager();
                $em->persist($photo);
                $em->flush();

                return $this->redirect($this->generateUrl('perso_galerie_homepage'));
            }
        }

        return $this->render('PersoGalerieBundle:Galerie:addPhoto.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}