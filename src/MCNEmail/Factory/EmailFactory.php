<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Factory;
use MCNEmail\Service,
    Zend\Mail\Transport,
    Zend\ServiceManager\ServiceManager,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class EmailFactory implements FactoryInterface
{
    /**
     * @throws Exception\LogicException
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \MCNEmail\Service\Email
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Config')['MCNEmail'];

        $options = new Service\EmailOptions(
            isSet($configuration['options']) ? $configuration['options'] : array()
        );

        $serviceLocator->setService('email_options', $options);

        return new Service\Email(
            $serviceLocator->get('email_service_template'),
            $serviceLocator->get('email_transport'),
            $options
        );
    }
}

