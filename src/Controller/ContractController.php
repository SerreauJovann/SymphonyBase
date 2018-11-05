<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    /**
     * @Route("/contract", name="contract")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $form = $this->createFormBuilder()
            ->add('nom',TextType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('prenom',TextType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('email',EmailType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('tel',TelType::class,array("constraints" => array(new NotBlank(),    new Length(array('min' => 5)))))
            ->add('ok',SubmitType::class)
            ->getForm();

        return $this->render('contract/index.html.twig', [
            'controller_name' => 'ContractController',
        ]);
    }
}
