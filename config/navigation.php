<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */
return array(
    'MCNEmail' => array(
        'type'  => 'mvc',
        'label' => 'MCNEmail',
        'order' => 5,

        'pages' => array(

            array(
                'label' => 'List alla mallar',
                'route' => 'admin/emailer/template_list',

                'controller' => 'email_template',
                'action'     => 'list',
            ),

            array(
                'type'       => 'mvc',
                'controller' => 'email_template',
                'action'     => 'edit',

                'visible' => false
            )
        )
    )
);
