<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\EmailModel;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Services\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, EmailSender $emailSender): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->save($contact, true);
            $user = (new User())
                ->setEmail('shadgramers@gmail.com')
                ->setFirstname('Sylvain')
                ->setLastname("Brenam");

            $email = (new EmailModel())->setTitle("Hello 👋" . $user->getFullName())
                ->setSubject("Nouveau message de contact")
                ->setContent("<br>De : " . $contact->getEmail()
                    . "<br> Nom : " . $contact->getName()
                    . "<br> Objet : " . $contact->getSubject()
                    . "<br><br> " . $contact->getContent()
                );
            $emailSender->sendEmailByMailJet($user, $email);
            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            $this->addFlash('contact_success', "Votre méssage a été envoyé avec succès, vous auez un retour au plus vite ! ");
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('contact_error', "Veuillez corriger les erreurs sur votre formulaire et rééssayer ");
        }
        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->save($contact, true);

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact, true);
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
