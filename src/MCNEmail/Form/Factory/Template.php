<?php

namespace MCNEMAIL\Form\Factory;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MCNEmail\Entity\Template as TemplateEntity;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Template
 */
class Template implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $formElementManager
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /**
         * @var $formElementManager \Zend\Form\FormElementManager
         * @var $objectManager \Doctrine\Common\Persistence\ObjectManager
         */
        $objectManager = $formElementManager->getServiceLocator()->get('mcn.objectManager');

        $builder = new AnnotationBuilder();
        $builder->getFormFactory()->setFormElementManager($formElementManager);

        $form = $builder->createForm('MCNEmail\Entity\Template');
        $form->setObject(new TemplateEntity());
        $form->setHydrator(new DoctrineObject($objectManager));

        $form->setValidationGroup(array('subject', 'template', 'description'));
        return $form;
    }
}
