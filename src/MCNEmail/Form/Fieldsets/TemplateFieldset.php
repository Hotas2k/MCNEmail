<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Form\Fieldset;
use Zend\Validator,
    Zend\Form\Fieldset,
    Zend\Stdlib\Hydrator\ClassMethods,
    Zend\InputFilter\InputFilterProviderInterface;

class TemplateFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('template_fieldset');

        // Supply the hydrator
        $this->setHydrator(new ClassMethods());

        $this->add(
            array(
                'name'       => 'name',
                'attributes' => array(

                    'disabled' => 'disabled'
                )
            )
        );

        $this->add(
            array(
                'name'       => 'subject',
                'attributes' => array(

                    'required' => 'required'
                )
            )
        );

        $this->add(
            array(
                'name'       => 'bcc',
            )
        );

        $this->add(
            array(
                 'name'       => 'template',
                 'attributes' => array(

                     'style'    => 'height: 200px',

                     // This causes a bug in google chrome
                     //'required' => 'required'
                 )
            )
        );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'subject' => array(

                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                        'options' => array(

                            'messages' => array(

                                Validator\NotEmpty::IS_EMPTY => 'Du måste ange en rubrik.'
                            )
                        )
                    )
                )
            ),

            'bcc' => array(

                'required'    => true,
                'allow_empty' => true
            ),

            'template' => array(

                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                        'options' => array(

                            'messages' => array(

                                Validator\NotEmpty::IS_EMPTY => 'Du måste ange en mall.'
                            )
                        )
                    )
                )
            )
        );
    }
}
