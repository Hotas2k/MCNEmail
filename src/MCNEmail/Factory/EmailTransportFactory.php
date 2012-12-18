<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Factory;
use Zend\Mail\Transport,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class EmailTransportFactory implements FactoryInterface
{
    /**
     * @throws Exception\InvalidArgumentException
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \Zend\Mail\Transport\TransportInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Config')['MCNEmail']['transport'];

        // If no type has been specified we default to send amil
        $configuration['type'] = isSet($configuration['type']) ? $configuration['type'] : 'sendmail';

        // Handle some stuff
        switch(strtolower($configuration['type']))
        {
            case 'smtp':
                if (! isSet($configuration['options'])) {

                    throw new Exception\InvalidArgumentException(
                        sprintf('The SMTP transport and cannot be instantiated without passing any options')
                    );
                }

                $options   = new Transport\SmtpOptions($configuration['options']);
                $transport = new Transport\Smtp($options);
                break;

            case 'sendmail':
                $transport = new Transport\Sendmail();
                break;

            default:
                throw new Exception\InvalidArgumentException(
                    sprintf('Unknown transport specified "%s".', $configuration['transport']['type'])
                );
        }

        return $transport;
    }
}
