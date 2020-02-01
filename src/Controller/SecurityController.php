<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        
        return $this->render('@EasyAdmin/page/login.html.twig', [
            // parameters usually defined in Symfony login forms
            'error' => $error,
            'last_username' => $lastUsername,
            // OPTIONAL parameters to customize the login form:
            // the string used to generate the CSRF token. If you don't define
            // this parameter, the login form won't include a CSRF token
            'csrf_token_intention' => 'authenticate',
            // the label displayed for the username form field (the |trans filter is applied to it)
            'username_label' => 'Логин',
            // the label displayed for the password form field (the |trans filter is applied to it)
            'password_label' => 'Пароль',
            // the label displayed for the Sign In form button (the |trans filter is applied to it)
            'sign_in_label' => 'Войти',
            'username_parameter' => 'username',
            // the 'name' HTML attribute of the <input> used for the password field (default: '_password')
            'password_parameter' => 'password',
        ]);
    }
    
    
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

}
