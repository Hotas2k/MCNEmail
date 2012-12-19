<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Form\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Annotation\AnnotationBuilder;

/**
 * @category MCNEmail
 * @package Form
 * @subpackage Factory
 */
class Template implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $builder = new AnnotationBuilder();

        /**
         * @var $form \Zend\Form\Form
         */
        $form = $builder->createForm('MCNEmail\Entity\Template');
        $form->setHydrator($serviceLocator->get('mcn.object.hydrator'));
        $form->setValidationGroup(array('subject','bcc','template'));

        return $form;
    }
}
