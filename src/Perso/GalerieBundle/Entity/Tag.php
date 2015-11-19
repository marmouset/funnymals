<?php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Perso\GalerieBundle\Entity\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="libTag", type="string", length=255)
     */
    private $libTag;

    /**
     * @ORM\ManyToMany(targetEntity="Perso\GalerieBundle\Entity\Photo", cascade={"persist"}, inversedBy="tags")
     *
     */
    private $photos;

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
     * Set libTag
     *
     * @param string $libTag
     *
     * @return Tag
     */
    public function setLibTag($libTag)
    {
        $this->libTag = $libTag;

        return $this;
    }

    /**
     * Get libTag
     *
     * @return string
     */
    public function getLibTag()
    {
        return $this->libTag;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add photo
     *
     * @param \Perso\GalerieBundle\Entity\Photo $photo
     *
     * @return Tag
     */
    public function addPhoto(\Perso\GalerieBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Perso\GalerieBundle\Entity\Photo $photo
     */
    public function removePhoto(\Perso\GalerieBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
