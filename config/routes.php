<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */
return array(
    'admin' => array(
        'child_routes' => array(

            'emailer' => array(
                'type'      => 'Zend\Mvc\Router\Http\Literal',
                'options'   => array(
                    'route' => '/email',
                ),

                'may_terminate' => false,
                'child_routes'  => array(
                    'template_list' => array(

                        'type'    => 'literal',
                        'options' => array(

                            'route'    => '/mall-lista',
                            'defaults' => array(

                                'controller' => 'email.template',
                                'action'     => 'list'
                            )
                        )
                    ),

                    'template_edit' => array(

                        'type'    => 'Segment',
                        'options' => array(

                            'route'    => '/redigera/:id',
                            'defaults' => array(

                                'controller' => 'email.template',
                                'action'     => 'edit'
                            ),

                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    )
                )
            )
        )
    )
);
