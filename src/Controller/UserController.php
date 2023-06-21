<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {

    public function getUsers(){

        $em = $this->getDoctrine()->getManager();
        $listUsers = $em->getRepository('App\Entity\Users')->findBy([], ['name' => 'ASC']);
        return $this->render('user/users.html.twig', [
            'listUsers' => $listUsers
        ]);

    }

    public function createUser(Request $request){

        $em = $this->getDoctrine()->getManager();

        $users = new \App\Entity\Users();

        $form_users = $this->createForm(\App\Form\UsersType::class, $users);
        $form_users->handleRequest($request);

        if($form_users->isSubmitted() && $form_users->isValid()){
            $users->setStatus(1);
            $em->persist($users);
            $em->flush();

            return $this->redirectToRoute('getUsers');
        }

        return $this->render('user/user_create.html.twig', [
            'form_users' => $form_users->createView()
        ]);

    }

    public function deleteUser($id){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('App\Entity\Users')->find($id);

        $users->setStatus(0);
        $em->persist($users);
        $em->flush();

        return $this->redirectToRoute('getUsers');
    }

    public function updateUser(Request $request, $id){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('App\Entity\Users')->find($id); 

        $form_users = $this->createForm(\App\Form\UsersType::class, $users);
        $form_users->handleRequest($request);

        if($form_users->isSubmitted() && $form_users->isValid()){
            $em->persist($users);
            $em->flush();

            return $this->redirectToRoute('getUsers');
        }

        return $this->render('user/user_update.html.twig', [
            'form_users' => $form_users->createView()
        ]);
    }
}