<?php

namespace Perso\GalerieBundle\Controller;

use Perso\GalerieBundle\Entity\Photo;
use Perso\GalerieBundle\Entity\VoteUserPhoto;
use Perso\GalerieBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Perso\GalerieBundle\Form\CommentaireType;
use Perso\GalerieBundle\Form\PhotoType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Symfony\Component\HttpFoundation\Response;

class GalerieController extends Controller
{
    //page d'accueil du site, affichage des photos
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

    //affichage d'une photo en particulier et de ses commentaires
    public function voirAction(Photo $photoGet)
    {
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository('PersoGalerieBundle:Photo')->find($photoGet->getId());

        if(null != $this->getUser())
        {
            $commentaire = new Commentaire;
            $commentaire->setUser($this->getUser());
            $form = $this->createForm(new CommentaireType, $commentaire);


            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {
                $form->submit($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    $photo->addCommentaire($commentaire);
                    $em->persist($commentaire);
                    $em->flush();

                    $flash = $this->get('translator')->trans('alert.info.commentOk');
                    $this->get('session')->getFlashBag()->add('success', $flash);
                }
            }
            return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'form' => $form->createView(), 'commentaires' => $photo->getCommentaires()));
        }
        else return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'commentaires' => $photo->getCommentaires()));
    }

    //gestion du vote pour une photo
    public function voteAction($typVote, Photo $photoGet)
    {
        $em = $this->getDoctrine()->getManager();

        //dans un premier temps on va vérifier si le user courant n'a pas déjà voté pour cette photo
        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $VoteUserPhotos = $em->getRepository("PersoGalerieBundle:VoteUserPhoto")->existVoteUser($photoGet,$this->getUser());

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
            if($typVote == 'down') $photoGet->setNbDown($photoGet->getNbDown() + 1);
            else $photoGet->setNbUp($photoGet->getNbUp() + 1);

            $em->persist($photoGet);

            $em->flush();

            $flash = $this->get('translator')->trans('alert.info.voteOk');
            $this->get('session')->getFlashBag()->add('success', $flash);

            return $this->redirect($this->generateUrl('perso_galerie_viewOne', array('slug' => $photoGet->getSlug())));
        }
        else
        {
            $flash = $this->get('translator')->trans('alert.info.voteNotOk');
            $this->get('session')->getFlashBag()->add('danger', $flash);

            return $this->redirect($this->generateUrl('perso_galerie_homepage'));
        }
    }

    //ajout d'une nouvelle photo
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
