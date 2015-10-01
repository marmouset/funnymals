<?php
// src/Perso/GalerieBundle/Tests/Entity/PhotoTest.php

namespace Perso\GalerieBundle\Tests\Entity;

use Perso\GalerieBundle\Entity\Photo;

class PhotoTest extends \PHPUnit_Framework_TestCase
{
    public function testgetDescriptif()
    {
        $maPhoto = new Photo();

        $this->assertEquals('hello-world', $blog->slugify('Hello World'));
    }
}