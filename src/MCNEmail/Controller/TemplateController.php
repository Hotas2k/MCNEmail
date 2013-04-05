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

namespace MCNEmail\Controller;

use MCNEmail\Service\Email as EmailService;
use MCNEmail\Service\Template as TemplateService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * @method \Zend\Http\Request getRequest
 * @method \Zend\Http\Response getResponse
 *
 * @package MCNEmail\Controller
 */
class TemplateController extends AbstractActionController
{
    /**
     * @var \MCNEmail\Service\Template
     */
    protected $service;

    /**
     * @param \MCNEmail\Service\Template $service
     */
    public function __construct(TemplateService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the template form
     *
     * @see \MCNEmail\Form\Factory\Template
     *
     * @return \Zend\Form\Form
     */
    protected function getForm()
    {
        return $this->getServiceLocator()->get('email.form.template');
    }

    /**
     * List all the templates
     *
     * @return array
     */
    public function listAction()
    {
        $options = array(
            'sort' => array(
                TemplateService::SORT_EMPTY_TEMPLATE => 'ASC'
            )
        );

        $templates = $this->service->fetchAll($options);

        return array('templates' => $templates);
    }

    /**
     * Edit a template
     *
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $id   = $this->params('id');
        $form = $this->getForm();

        $template = $this->service->getById($id);

        if (! $template) {

            return $this->getResponse()->setStatusCode(404);
        }

        $form->bind($template);

        if ($this->getRequest()->isPost()) {

            $form->setData($_POST);

            if ($form->isValid()) {

                $this->service->save($template);

                return $this->message('Mallen har sparats', 'Vad vill du göra nu?', 'admin/emailer/template_list');
            }
        }

        return array('form' => $form,'template' => $template);
    }
}
