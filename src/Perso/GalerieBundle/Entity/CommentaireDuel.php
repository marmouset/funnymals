<?php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireDuel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Perso\GalerieBundle\Entity\CommentaireDuelRepository")
 */
class CommentaireDuel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\ManyToOne(targetEntity="Perso\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Perso\GalerieBundle\Entity\Duel", inversedBy="commentairesDuel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $duel;

    /**
     * CommentaireDuel constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Commentaire
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Commentaire
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \Perso\UserBundle\Entity\User $user
     *
     * @return Commentaire
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


    /**
     * Set duel
     *
     * @param \Perso\GalerieBundle\Entity\Duel $duel
     *
     * @return CommentaireDuel
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
}
