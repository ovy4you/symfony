<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;


class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function indexAction(Request $request)
    {

        $form = $this->createFormBuilder(null, [
            'data_class' => 'AppBundle\Entity\User'
        ])
            ->add('username','text')
            ->add('email','text')
            ->add('password','repeated',['type'=>'password'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($this->encodePassword($user,$user->getPassword()),$user->getPassword());

            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('login'));
        }


        return $this->render('register/index.html.twig',
            ['form' => $form->createView()]);

    }


    private function encodePassword(User $user,$plainTextPassword){
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $plainTextPassword);
        return $password;
    }


}
