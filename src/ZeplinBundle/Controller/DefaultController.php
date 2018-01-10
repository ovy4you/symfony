<?php

namespace ZeplinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use ZeplinBundle\Entity\Posts;
use ZeplinBundle\Entity\Profile;
use ZeplinBundle\Form\PostType;
use ZeplinBundle\Entity\Images;
use ZeplinBundle\Form\ImageType;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/", name="admin")
     */
    public function indexAction()
    {
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('ZeplinBundle:Images')->findAllByUserId($this->getUserProfileId());
        return $this->render('ZeplinBundle:Default:index.html.twig', ['content' => $repo]);
    }


    /**
     * @Route("/admin/new",name="admin_new")
     */
    public function newAction(Request $request)
    {


        $post = new Images();
        $form = $this->createForm(ImageType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $post->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $post->setImage($fileName);
            $post->setTime(new \DateTime());
            $post->setUserId($this->getUser()->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('admin'));
        }

        return $this->render('ZeplinBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/admin/edit/{id}",name="admin_edit")
     */
    public function editAction(Request $request)
    {

        $id = $request->get('id');

        $em = $this->container->get('doctrine')->getManager();

        $image = $em->getRepository('ZeplinBundle:Images')->findOneById($id);

        if ($image) {
            if (!$post = $em->getRepository('ZeplinBundle:Posts')->findOneByImage($id)) {
                $post = new Posts();
                $post->setImage($id);
            }

            $post->setTime(new \DateTime());


            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->merge($post);
                $em->flush();
                return $this->render('ZeplinBundle:Default:modals/messages.html.twig', ['message' => 'Data was saved']);
            }
            return $this->render('ZeplinBundle:Default:modals/edit.html.twig', [
                'form' => $form->createView(), 'data' => $image
            ]);
        } else {
            return $this->render('ZeplinBundle:Default:modals/messages.html.twig', ['message' => 'Invalid request'
            ]);
        }

    }


    /**
     * @Route("/admin/profile-pic/",name="profile-pic")
     */
    public function profilePicAction()
    {

        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('ZeplinBundle:Profile')->getProfilePicture($this->getUserProfileId());
        return $this->render('ZeplinBundle:Default:partials/profile_pic.html.twig', ['content' => $repo]);
    }


    /**
     * @Route("/admin/delete/",name="admin_delete")
     */
    public function deleteAction(Request $request)
    {

        $params = $request->get('post');

        if (!empty($params['image'])) {

            $imageId = $params['image'];

            $em = $this->container->get('doctrine')->getManager();

            $image = $em->getRepository('ZeplinBundle:Images')->find($imageId);
            $post = $em->getRepository('ZeplinBundle:Posts')->findOneByImage($imageId);

            $form = $this->createForm(PostType::class, $post);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($image) {
                    if ($this->deletePicture($image->getImage())) {

                        $em->remove($image);

                        if ($post) {
                            $em->remove($post);
                        }

                        $em->flush();
                        $message = 'Data was deleted';
                    } else {
                        $message = 'Data was not deleted';
                    }
                }
            } else {
                $message = 'An error occurred';
            }
        } else {
            $message = 'Invalid request';
        }

        return $this->render('ZeplinBundle:Default:modals/messages.html.twig', ['message' => $message
        ]);

    }


    /**
     * @Route("/admin/update-profile-pic/{id}",name="update-profile-pic")
     */
    public function updateProfilePicAction(Request $request)
    {

        $imgId = $request->get('id');

        $em = $this->container->get('doctrine')->getManager();

        $image = $em->getRepository('ZeplinBundle:Images')->findOneById($imgId);

        if (!$profile = $em->getRepository('ZeplinBundle:Profile')->findOneByUserId($this->getUserProfileId())) {
            $profile = new Profile();
            $profile->setUserId($this->getUserProfileId());
        }

        $profile->setImageId($image);

        $profile->setTime(new \DateTime());


        $em->persist($profile);

        $em->flush();

        return $this->render('ZeplinBundle:Default:modals/messages.html.twig', ['message' => 'Profile picture was updated'
        ]);
    }


    private function deletePicture($image)
    {
        if (file_exists($this->getParameter('images_directory') . $image)) {
            return unlink($this->getParameter('images_directory') . $image);
        } else {
            throw new FileNotFoundException();
        }
    }


    private function getUserProfileId()
    {
        return $this->getUser()->getId();
    }

}
