<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Repository\ContactsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class FormController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ContactsRepository $contactsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request,EntityManagerInterface $entityManager,ContactsRepository $contactsRepository)
    {
        $form = $this->createFormBuilder()
            ->add('nom',TextType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('prenom',TextType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('email',EmailType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('tel',TelType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('ok',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityContact = new Contacts();
            $entityContact->setName($form->get('nom')->getData());
            $entityContact->setPrenom($form->get('prenom')->getData());
            $entityContact->setEmail($form->get('email')->getData());
            $entityContact->setTel($form->get('tel')->getData()    );
            $entityManager->persist($entityContact);
            $entityManager->flush();

            return $this->render('firstResponseTemplate.html.twig', array(
                'data' => $form->getData(),
            ));
        }

        $result = $contactsRepository->findAll();

        return $this->render('firstFormTemplate.html.twig', array(
            'data' => $form->createView(),
            'client' =>$result,
        ));
    }

    /**
     * @Route("/contact/liste", name="form")

     */
    public function liste(ContactsRepository $contactsRepository)
    {
        $result = $contactsRepository->findAll();

        return $this->render('contract/index.html.twig', array(
            'data' => $result,
        ));
    }
}
