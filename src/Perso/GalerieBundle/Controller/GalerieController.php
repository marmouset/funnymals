<?php

namespace Perso\GalerieBundle\Controller;

//use Perso\GalerieBundle\AugmenteVues\AugmenteVuesEvent;
//use Perso\GalerieBundle\AugmenteVues\AugmenteVuesEvents;
use Perso\GalerieBundle\Entity\Photo;
use Perso\GalerieBundle\Entity\Tag;
use Perso\GalerieBundle\Entity\VoteUserPhoto;
use Perso\GalerieBundle\Entity\VoteUserDuel;
use Perso\GalerieBundle\Entity\Commentaire;
use Perso\GalerieBundle\Entity\CommentaireDuel;
use Perso\GalerieBundle\Entity\Duel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Perso\GalerieBundle\Form\CommentaireType;
use Perso\GalerieBundle\Form\PhotoType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use \DateTime;

//use Perso\GalerieBundle\AugmenteVues;
use Symfony\Component\HttpFoundation\Response;

class GalerieController extends Controller
{
    //page d'accueil du site, affichage des photos
    public function indexAction($page, $tags = null)
    {
        //return $this->render('PersoGalerieBundle:Galerie:index.html.twig', array('name' => $name));
        $em = $this->getDoctrine()->getManager();
        //$photos = $em->getRepository('PersoGalerieBundle:Photo')->findAll();

        //récupération via le formulaire de recherche
        if(isset($_POST["inputRechTags"])) $tags = $_POST["inputRechTags"];

        if(is_null($tags)) $photos = $em->getRepository('PersoGalerieBundle:Photo')->getAllPhotosDesc($this->getParameter('nb_img_by_page'), $page);
        else {
            $photos = $em->getRepository('PersoGalerieBundle:Photo')->getAllPhotosTagDesc($this->getParameter('nb_img_by_page'), $page, $tags);
        }

        /*$antispam = $this->container->get('perso_galerieBundle.antispam');
        $text = 'gfdgfd@fdgfd.com gfdgfd@fdgfd.com ';
        // Je pars du principe que $text contient le texte d'un message quelconque
        if ($antispam->isSpam($text)) {
            throw new \Exception('Votre message a été détecté comme spam !');
        }
        */

        return $this->render('PersoGalerieBundle:Galerie:index.html.twig', array('photos' => $photos,
            'page'       => $page,
            'nombrePage' => ceil(count($photos)/$this->getParameter('nb_img_by_page'))));
    }

    //affichage d'une photo en particulier et de ses commentaires
    public function voirAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();
        //$photo = $em->getRepository('PersoGalerieBundle:Photo')->find($photoGet->getId());

        //je crée mon évènement avec les bons paramètres
        /*
        $event = new AugmenteVuesEvent($photo, $this->getUser());
        $this->get('event_dispatcher')
            ->dispatch(AugmenteVuesEvents::onViewPhoto, $event);

        $photo->setNbVues($event.getPhoto());
        */

        //on est limité à 1 vue par user et par photo
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if (!($photo->getUsersView()->contains($this->getUser()))) {
                $photo->addUsersView($this->getUser());
                $photo->setNbVues($photo->getNbVues() + 1);
                $em->persist($photo);
                $em->flush();
            }
        }

        //on va récupérer tous les commentaires par ordre décroissant sur le champ createdAt
        $commentairesRecup = $em->getRepository('PersoGalerieBundle:Commentaire')->getCommentairesByPhotoDesc($photo);

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

                    return $this->redirect($this->generateUrl('perso_galerie_viewOne', array('slug' => $photo->getSlug())));
                }
            }

            return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'form'      => $form->createView(), 'commentaires' => $commentairesRecup));
        }

        return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'commentaires' => $commentairesRecup));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function tmpAddDuelAction()
    {
        $myDuel = new Duel;
        $em = $this->getDoctrine()->getManager();

        $photoA = $em->getRepository("PersoGalerieBundle:Photo")->find(6);
        $photoB = $em->getRepository("PersoGalerieBundle:Photo")->find(7);


        $myDuel->setPhotoA($photoA);
        $myDuel->setPhotoB($photoB);

        $em->persist($myDuel);
        $em->flush();

        return new Response("ok");
    }


    public function voirDuelsAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $duels = $em->getRepository('PersoGalerieBundle:Duel')->getAllDuelsDesc($this->getParameter('nb_duels_by_page'), $page);
        //$duels = $em->getRepository('PersoGalerieBundle:Duel')->findBy(array('photoA' => 3));

        //$chaine = var_dump($duels);
        //return new Response($chaine);
        $tabDureesRestantes = array();
        $tabCommentaires = array();
        $tabFiniDuree = array();
        $tabForms = array();
        foreach($duels as $duel)
        {
            $commentaireDuel = new CommentaireDuel();

            if (true === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $tabForms[$duel->getId()] = $this->createFormBuilder($commentaireDuel)
                    ->add('texte', 'text', array('label' => 'Un commentaire ?'))
                    ->getForm()
                    ->createView();
            }

            $dateFrom = new DateTime();
            $dateNow = $duel->getDateFin();

            //on envoie directement l'info à la vue comme quoi un duel est terminé ou non
            if($dateNow <= $dateFrom) $tabFiniDuree[$duel->getId()] = true;
            else $tabFiniDuree[$duel->getId()] = false;

            $interval = $dateNow->diff($dateFrom);

            $nbHeures = $interval->d * 24 + $interval->h;
            $nbMinutes = $interval->i;
            $nbSecondes = $interval->s;
            if($nbHeures < 10) $nbHeures= '0'.$nbHeures;
            if($nbMinutes < 10) $nbMinutes= '0'.$nbMinutes;
            if($nbSecondes < 10) $nbSecondes= '0'.$nbSecondes;

            $tabDureesRestantes[$duel->getId()] = "$nbHeures:$nbMinutes:$nbSecondes";

            $commentairesRecup = $em->getRepository('PersoGalerieBundle:CommentaireDuel')->getCommentairesByDuelDesc($duel);
            $tabCommentaires[$duel->getId()] = $commentairesRecup;

        }

        /*
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form1->bind($request);
            if ($form1->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($commentateur);
                $em->flush();
                return new Response('ok commentateur');

            }
        }
        */

        /*
        //on va récupérer tous les commentaires par ordre décroissant sur le champ createdAt
        $commentairesRecup = $em->getRepository('PersoGalerieBundle:Commentaire')->getCommentairesByPhotoDesc($photo);

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

                    return $this->redirect($this->generateUrl('perso_galerie_viewOne', array('slug' => $photo->getSlug())));
                }
            }

            return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'form'      => $form->createView(), 'commentaires' => $commentairesRecup));
        }

        return $this->render('PersoGalerieBundle:Galerie:voir.html.twig', array('photo' => $photo, 'commentaires' => $commentairesRecup));
        */

        return $this->render('PersoGalerieBundle:Galerie:indexDuels.html.twig', array('duels' => $duels,
            'page'       => $page,
            'tabDureesRestantes' => $tabDureesRestantes,
            'tabFiniDuree' => $tabFiniDuree,
            'commentaires' => $tabCommentaires,
            'formulaires' => $tabForms,
            'nombrePage' => ceil(count($duels)/$this->getParameter('nb_duels_by_page'))));

    }


    /**
     * @ParamConverter("duel", options={"mapping": {"idDuel": "id"}})
     * @ParamConverter("photo", options={"mapping": {"slug": "slug"}})
     */
    public function voteDuelAction(Duel $duel, Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();

        //dans un premier temps on va vérifier si le user courant n'a pas déjà voté pour cette photo
        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        //on doit vérifier si on a déjà voté pour ce duel
        if($em->getRepository('PersoGalerieBundle:VoteUserDuel')->find(array('duel' => $duel, 'user' => $this->getUser())))
            $respTmp = true;
        else $respTmp = false;
        if($respTmp)
        {
            $flash = $this->get('translator')->trans('alert.info.voteDuelNotOk');
            $this->get('session')->getFlashBag()->add('danger', $flash);
            return $this->redirect($this->generateUrl('perso_galerie_duels'));
            //throw new \Exception($this->get('translator')->trans('dejaVoteDuel'));
        }

        //on vérifie si la date n'est pas dépassée
        if($duel->getDateFin() <= new \DateTime())
        {
            $flash = $this->get('translator')->trans('alert.info.voteDuelDateNotOk');
            $this->get('session')->getFlashBag()->add('danger', $flash);
            return $this->redirect($this->generateUrl('perso_galerie_duels'));
        }

        //maintenant, on va ajouter le vote
        $voteUserDuel = new VoteUserDuel();
        $voteUserDuel->setUser($this->getUser());
        $voteUserDuel->setDuel($duel);
        $em->persist($voteUserDuel);

        if($duel->getPhotoA() === $photo) $duel->setVoteA($duel->getVoteA() + 1);
        else $duel->setVoteB($duel->getVoteB() + 1);
        $em->persist($duel);

        $em->flush();

        $flash = $this->get('translator')->trans('alert.info.voteDuelOk');
        $this->get('session')->getFlashBag()->add('success', $flash);

        //return $this->render('PersoGalerieBundle:Galerie:indexDuels.html.twig', array());
        return $this->redirect($this->generateUrl('perso_galerie_duels'));
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

            //return $this->redirect($this->generateUrl('perso_galerie_homepage'));
            return $this->redirect($this->generateUrl('perso_galerie_viewOne', array('slug' => $photoGet->getSlug())));
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

                $em = $this->getDoctrine()->getManager();

                $photo->setUser($this->getUser());

                //on va traiter les tags maintenant
                $recupTags = $form->get('myTags')->getData();
                $tabRecupTags = explode(',',$recupTags);

                //on analyse pour chaque tag s'il existe
                foreach($tabRecupTags as $eachTag)
                {
                    $eachTagPure = trim($eachTag);
                    //$tagTrouv = $em->getRepository('PersoGalerieBundle:Tag')->findOneByLibTag($eachTagPure);
                    $tagTrouv = $em->getRepository('PersoGalerieBundle:Tag')->findOneBy(array('libTag' => $eachTagPure));
                    if(null === $tagTrouv)
                    {
                        //on ajoute dans l'entité Tag et on lie à la photo
                        $newTag = new Tag;
                        $newTag->setLibTag($eachTagPure);
                        $em->persist($newTag);
                        $photo->addTag($newTag);
                    }
                    else
                    {
                        //on se contente de lier le tag existant à la photo
                        $photo->addTag($tagTrouv);
                    }
                }

                $this->get('session')->getFlashBag()->add('success', 'Votre photo a bien été validée !');

                //$em = $this->getDoctrine()->getManager();
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
