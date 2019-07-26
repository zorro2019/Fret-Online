<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request , ObjectManager $manager)
    {
        $contact = new Contact();
        $succes = false;
        $form = $this->createForm(ContactFormType::class , $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($contact);
            $manager->flush();
           $succes = true;
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView() ,
            'succes' =>$succes
        ]);
    }

}
