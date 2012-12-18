<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Form\Template;
use Zend\Form\Form,
    Zend\InputFilter\InputFilter,
    Zend\Stdlib\Hydrator\ClassMethods;

class Edit extends Form
{
    public function __construct()
    {
        parent::__construct('MCNEmail_form_template_edit');

        $this->setHydrator(new ClassMethods())
             ->setInputFilter(new InputFilter())
             ->add(
                array(
                    'name'    => 'template_fieldset',
                    'type'    => 'MCNEmail\Form\Fieldset\TemplateFieldset',
                    'options' => array(

                        'use_as_base_fieldset' => true
                    )
                )
            );
    }
}
