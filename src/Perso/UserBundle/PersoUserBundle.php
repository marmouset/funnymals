<?php

namespace Perso\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PersoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
