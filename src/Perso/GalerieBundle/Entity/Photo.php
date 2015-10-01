<?php

namespace Perso\GalerieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Perso\GalerieBundle\Entity\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo
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
     * @Assert\Length(min = "9",max = "80")
     * @ORM\Column(name="legende", type="string", length=255)
     */
    private $legende;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     * @Assert\Length(min = "30",max = "200")
     * @ORM\Column(name="descriptif", type="string", length=255)
     */
    private $descriptif;

    /**
     * @ORM\ManyToOne(targetEntity="Perso\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     * @Assert\Length(min = "0")
     * @ORM\Column(name="nbUp", type="integer")
     */
    private $nbUp;

    /**
     * @var integer
     * @Assert\Length(min = "0")
     * @ORM\Column(name="nbDown", type="integer")
     */
    private $nbDown;

    /*
    * @Assert\Image(maxSize="2M")
    * @Assert\File
    */
    protected $file;

    /**
     * @Gedmo\Slug(fields={"legende"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;


    private $tempFilename;

    public function __construct()
    {
        //$this->get('security.context')->getToken()->getUser();
        //$this->user = $this->curr
    }

    public function getFile()
    {
        return $this->file;
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
     * Set legende
     *
     * @param string $legende
     *
     * @return Photo
     */
    public function setLegende($legende)
    {
        $this->legende = $legende;
        return $this;
    }

    /**
     * Get legende
     *
     * @return string
     */
    public function getLegende()
    {
        return $this->legende;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Photo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }




    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe d�j� un autre
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        // On v�rifie si on avait d�j� un fichier pour cette entit�
        if (null !== $this->url) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->url;

            // On r�initialise les valeurs des attributs url et alt
            $this->url = null;
            //$this->alt = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }


        $this->url = $this->file->getClientOriginalName();//guessExtension();

        // Et on g�n�re l'attribut alt de la balise <img>, � la valeur du nom du fichier sur le PC de l'internaute
        //$this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On d�place le fichier envoy� dans le r�pertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le r�pertoire de destination
            $this->url   // Le nom du fichier � cr�er, ici � id.extension �
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il d�pend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas acc�s � l'id, on utilise notre nom sauvegard�
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     *
     * @return Photo
     */
    public function setDescriptif($descriptif)
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * Set user
     *
     * @param \Perso\UserBundle\Entity\User $user
     *
     * @return Photo
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Photo
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nbUp
     *
     * @param integer $nbUp
     *
     * @return Photo
     */
    public function setNbUp($nbUp)
    {
        $this->nbUp = $nbUp;

        return $this;
    }

    /**
     * Get nbUp
     *
     * @return integer
     */
    public function getNbUp()
    {
        return $this->nbUp;
    }

    /**
     * Set nbDown
     *
     * @param integer $nbDown
     *
     * @return Photo
     */
    public function setNbDown($nbDown)
    {
        $this->nbDown = $nbDown;

        return $this;
    }

    /**
     * Get nbDown
     *
     * @return integer
     */
    public function getNbDown()
    {
        return $this->nbDown;
    }

}
