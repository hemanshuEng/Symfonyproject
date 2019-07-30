<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{
    /**
     * @Route ("/login",name="security_login")
     */
    public function login()
    { }

    /**
     * @Route ("/logout",name="security_logout")
     */
    public function logout()
    { }
}
