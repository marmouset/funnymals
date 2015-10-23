<?php

namespace Perso\GalerieBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Perso\GalerieBundle\Entity\Tag;

class StringToTagsTransformer implements DataTransformerInterface
{

    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function reverseTransform($ftags)
    {
        $tags = new ArrayCollection();
        $tag = strtok($ftags, ",");
        while($tag !== false) {
            $itag = new Tag();
            $itag->setLibTag($tag);
            if(!$tags->contains($itag))
                $tags[] = $itag;
            $tag = strtok(",");
        }
        return $tags;
    }

    public function transform($tags)
    {
        $ftags = "";
        if($tags != null) {
            foreach($tags as $tag)
                $ftags = $ftags.','.$tag->getLibTag();

        }
        return $ftags;
    }
}