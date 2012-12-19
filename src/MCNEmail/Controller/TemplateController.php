<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Controller;

use MCNEmail\Service\Email as EmailService;
use MCNEmail\Service\Template as TemplateService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * @method \Zend\Http\Request getRequest
 * @method \Zend\Http\Response getResponse
 * @method \Zend\Mvc\Controller\Plugin\Params params
 * @method \Zend\Mvc\Controller\Plugin\Redirect redirect
 *
 * @category MCNEmail
 * @package Controller
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

        return array(
            'templates' => $templates
        );
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

                return $this->message('Mallen har sparats', 'Happ, vafan ska du göra nu då?', 'admin/emailer/template_list');
            }
        }

        return array('form' => $form,'template' => $template);
    }
}
