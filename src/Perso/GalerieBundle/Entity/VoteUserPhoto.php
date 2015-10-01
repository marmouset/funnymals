<?php
// src/Perso/GalerieBundle/Entity/VoteUserPhoto.php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Perso\GalerieBundle\Entity\VoteUserPhotoRepository")
 */
class VoteUserPhoto
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Perso\GalerieBundle\Entity\Photo")
     */
    private $photo;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Perso\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return VoteUserPhoto
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set photo
     *
     * @param \Perso\GalerieBundle\Entity\Photo $photo
     *
     * @return VoteUserPhoto
     */
    public function setPhoto(\Perso\GalerieBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Perso\GalerieBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \Perso\UserBundle\Entity\User $user
     *
     * @return VoteUserPhoto
     */
    public function setUser(\Perso\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Perso\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
