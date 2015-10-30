<?php
// src/Perso/GalerieBundle/Entity/VoteUserDuel.php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Perso\GalerieBundle\Entity\VoteUserDuelRepository")
 */
class VoteUserDuel
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Perso\GalerieBundle\Entity\Duel")
     */
    private $duel;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Perso\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var \DateTime $date
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
     * @return VoteUserDuel
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }


    public function __construct()
    {
        $this->date = new \Datetime();

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
     * Set duel
     *
     * @param \Perso\GalerieBundle\Entity\Duel $duel
     *
     * @return VoteUserDuel
     */
    public function setDuel(\Perso\GalerieBundle\Entity\Duel $duel)
    {
        $this->duel = $duel;

        return $this;
    }

    /**
     * Get duel
     *
     * @return \Perso\GalerieBundle\Entity\Duel
     */
    public function getDuel()
    {
        return $this->duel;
    }

    /**
     * Set user
     *
     * @param \Perso\UserBundle\Entity\User $user
     *
     * @return VoteUserDuel
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
