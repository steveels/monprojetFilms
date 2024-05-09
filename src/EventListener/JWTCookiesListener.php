<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;



class JWTCookiesListener
{
    public function onKernelRequest(RequestEvent $event){
        $request= $event->getRequest();
        $cookieName = 'BEARER';

        if($request->cookies->has($cookieName)){
            $token = $request->cookies->get($cookieName);
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        
    }
}