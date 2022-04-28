<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ContactData;
use App\Form\ContactDataType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactDataController extends AbstractController
{
    #[Route('/', name: 'contact_data')]
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $contactData = new ContactData();

        $form = $this->createForm(ContactDataType::class, $contactData);

        $form->handleRequest($request);

        $message = "";

        $errors = $validator->validate($contactData);

        $submitted = false;

        if ($form->isSubmitted() && $form->isValid() && count($errors) == 0)
        {
            $contactData = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactData);
            $entityManager->flush();

            $message = "Your information has been submitted";
            $submitted = true;
        }
        elseif ($form->isSubmitted() && !$form->isValid())
        {
            $message = "Please fill in all fields";
        }

        return $this->render('contact_data/index.html.twig', [
            'form' => $form->createView(),
            'content' => $content,
            'submitted' => $submitted,
        ]);
    }
}
