<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Factory;

use MCNEmail\Service;
use Zend\Mail\Transport;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MCNEmail\Options\EmailOptions;
use Zend\Log\LoggerInterface;

/**
 * @category MCNEmail
 * @package
 */
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

        $options = new EmailOptions(
            isSet($configuration['options']) ? $configuration['options'] : array()
        );

        $service = new Service\Email(
            $serviceLocator->get('mcn.service.email.template'),
            $serviceLocator->get('mcn.service.email.transport'),
            $options
        );

        if ($serviceLocator->has('logger')) {

            $logger = $serviceLocator->get('logger');

            if ($logger instanceof LoggerInterface) {

                $service->setLogger($logger);
            }
        }

        return $service;
    }
}

