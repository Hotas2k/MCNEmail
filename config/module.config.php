<?php
/**
 * Copyright (c) 2011-2013 Antoine Hedgecock.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
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
                    'module/MCNEmail/src/MCNEmail/Entity/',
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