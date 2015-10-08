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
}

