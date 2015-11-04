<?php

namespace Perso\GalerieBundle\EventListener;

use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

//permet de mettre de c�t� la derni�re route matched, pour rediriger le visiteurs
// (par exemple s'il a choisi une autre langue)
class LastRouteListener
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        // on �vite les sous requ�tes
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();

        $routeName = $request->get('_route');
        $routeParams = $request->get('_route_params');
        if ($routeName[0] == '_') {
            return;
        }
        $routeData = ['name' => $routeName, 'params' => $routeParams];

        // on n'enregistre pas deux fois la m�me route
        $thisRoute = $session->get('this_route', []);
        if ($thisRoute == $routeData) {
            return;
        }
        $session->set('last_route', $thisRoute);
        $session->set('this_route', $routeData);
    }
}