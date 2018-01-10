<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }



    /**
     * @Route("/login", name="login")
     */

    public function loginAction(Request $request)
    {


        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('AppBundle:User');



        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));


    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

}
