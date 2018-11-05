<?php
require_once "../vendor/autoload.php";

use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Symfony\Component\Translation\Translator;


// Contruction du formulaire

define('DEFAULT_FORM_THEME', 'form_div_layout.html.twig');
define('VENDOR_DIR', realpath(__DIR__ . '/../vendor'));
define('VENDOR_TWIG_BRIDGE_DIR', VENDOR_DIR . '/symfony/twig-bridge');
define('VIEWS_DIR', realpath(__DIR__ . '/views'));
define('TRANS_DIR', VENDOR_DIR.'/symfony/form/Resources/translations/validators.fr.xlf');


$translator = new Translator('en');
// somehow load some translations into it
$translator->addLoader('xlf', new XliffFileLoader());
$translator->addResource(
    'xlf',
    TRANS_DIR,
    'en'
);


$twig = new Environment(new Twig_Loader_Filesystem(array(
    VIEWS_DIR,
    VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form',
)));

$formEngine = new TwigRendererEngine(array(DEFAULT_FORM_THEME), $twig);
$twig->addRuntimeLoader(new FactoryRuntimeLoader(array(
    FormRenderer::class => function () use ($formEngine) {
        return new FormRenderer($formEngine);
    },
)));
$twig->addExtension( new FormExtension() );
$twig->addExtension( new TranslationExtension($translator));

$csrfTokenManager = new CsrfTokenManager();
$validator = Validation::createValidator();
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension( new ValidatorExtension( $validator ))
    ->addExtension( new CsrfExtension( $csrfTokenManager ))
    ->getFormFactory();

$form = $formFactory->createBuilder()
    ->add( 'nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(array('min' => 15))
            ]
        ] )
    ->add( 'Prenom', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
        'constraints' => [
            new NotBlank(),
            new Length(array('min' => 15))
        ]
    ] )
    ->add( 'Email', \Symfony\Component\Form\Extension\Core\Type\EmailType::class, [
        'constraints' => [
            new NotBlank(),
            new Length(array('min' => 5))
        ]
    ] )
    ->add( 'Tel', \Symfony\Component\Form\Extension\Core\Type\TelType::class, [
        'constraints' => [
            new NotBlank(),
            new Length(array('min' => 15))
        ]
    ] )
    ->getForm();

// Mécanisme de soumission

$form->handleRequest();

if ($form->isSubmitted() && $form->isValid()) {
    echo $twig->render('firstResponseTemplate.html.twig', array(
        'data' => $form->getData(),
    ));
    return;
}

echo $twig->render('firstFormTemplate.html.twig', array(
    'form' => $form->createView(),
));