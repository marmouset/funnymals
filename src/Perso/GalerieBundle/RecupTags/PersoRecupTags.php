<?php
// Perso/GalerieBundle/RecupTags/PersoRecupTags.php
namespace Perso\GalerieBundle\RecupTags;

use Perso\GalerieBundle\Entity\Tag;
use Perso\GalerieBundle\Entity\TagRepository;
use Doctrine\ORM\EntityManager;

class PersoRecupTags extends \Twig_Extension
{
    /*
     * permet d'afficher les tags dans le footer de base.html.twig
     * donc dans toutes les vues
     */

    private $em;

    public function __construct(EntityManager $entitymanager)
    {
        $this->em = $entitymanager;
    }

    public function displayTags()
    {
        return $this->em->getRepository("PersoGalerieBundle:Tag")->betterTags();
    }

    /*
    * Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    */
    public function getFunctions()
    {
        return array(
            'displayTags' => new \Twig_Function_Method($this, 'displayTags')
        );
    }

    /*
     * La méthode getName() identifie votre extension Twig, elle est obligatoire
     */
    public function getName()
    {
        return 'PersoRecupTags';
    }

}