<?php
//src\Perso\GalerieBundle\AugmenteVues\AugmenteVuesEvent.php

namespace Perso\GalerieBundle\AugmenteVues;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;
use Perso\GalerieBundle\Entity\Photo;

class AugmenteVuesEvent extends Event
{
    protected $photo;
    protected $user;

    public function __construct(Photo $photoGet, UserInterface $user)
    {
        $this->photo  = $photoGet;
        $this->user     = $user;
    }

    // Le listener doit avoir accès à la photo
    public function getPhoto()
    {
        return $this->photo;
    }

    // Le listener doit pouvoir modifier la photo (son attribut nbVues)
    public function setPhoto($photo)
    {
        //l'auteur de la photo ne compte pas dans le compteur du nombre de fois vu
        if($this->photo->getUser() != $this->getUser())
        {
            $nbVues = $this->$photo->getNbVues();
            $nbVues++;
            $this->$photo->setNbVues($nbVues);
        }
        return $this->$photo;
    }

    // Le listener doit avoir accès à l'utilisateur
    public function getUser()
    {
        return $this->user;
    }

}