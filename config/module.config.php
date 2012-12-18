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
            'MCNEmail/template/list' => __DIR__ . '/../view/template/list.phtml',
            'MCNEmail/template/edit' => __DIR__ . '/../view/template/edit.phtml'
        )
    ),

    'service_manager' => array(

        'invokables' => array(

            'email_form_template_edit' => 'MCNEmail\Form\Template\Edit'
        )
    ),

    'navigation' => array(
        'admin' => include __DIR__ . '/navigation.php'
    ),

    'router' => array(
        'routes' => include __DIR__ . '/routes.php'
    )
);
