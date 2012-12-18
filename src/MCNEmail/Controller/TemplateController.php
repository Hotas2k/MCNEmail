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
 * @method \Zend\Mvc\Controller\Plugin\Params param
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

    public function editAction()
    {
        $form = $this->getServiceLocator()
                     ->get('email_form_template_edit');

        $id = $this->params('id');

        $template = $this->service->getById($id);

        if (! $template) {

            $this->response->setStatusCode(404);
            return null;
        }


        $form->bind($template);

        if ($this->getRequest()->isPost()) {

            $form->setData($_POST);

            if ($form->isValid()) {

                $this->getService()
                     ->save($template);

                return $this->message('Mallen har sparats', 'Happ, vafan ska du göra nu då?', 'admin/MCNEmail/template_list');
            }
        }

        return array(
            'form'     => $form,
            'template' => $template
        );
    }
}
