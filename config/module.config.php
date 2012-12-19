<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */
return array(

    'MCNEmail' => array(

        'service' => array(

            // Mailchimp configuration
            'mailchimp'  => array(
                'apiKey' => 'e096a5f539a1563a0c9a2bf2f0dc369b-us4'
            )
        )
    ),

    'doctrine' => array(
        'driver' => array(
            'email_annotation_driver' => array(
                'class'     => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths'     => array(
                    'devmodules/MCNEmail/src/MCNEmail/Entity/',
                ),
            ),

            'orm_default' => array(
                'drivers' => array(
                    'MCNEmail\Entity' => 'email_annotation_driver'
                )
            )
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'mcn-email/template/list' => __DIR__ . '/../view/template/list.phtml',
            'mcn-email/template/edit' => __DIR__ . '/../view/template/edit.phtml'
        )
    ),

    'service_manager' => array(

        'factories' => array(

            'email.form.template' => 'MCNEmail\Form\Factory\Template'
        )
    ),

    'navigation' => array(
        'admin' => include __DIR__ . '/navigation.php'
    ),

    'router' => array(
        'routes' => include __DIR__ . '/routes.php'
    )
);
