<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Factory;
use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

use MCNEmail\Service\Mailchimp\Mailchimp as MailchimpService,
    MCNEmail\Service\Mailchimp\MailchimpOptions;

class Mailchimp implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['MCNEmail'];

        if (! isSet($config['service']['mailchimp'])) {

            throw new Exception\InvalidArgumentException(
                sprintf('No configuration was specified for mailchimp')
            );
        }

        return new MailchimpService(new MailchimpOptions($config['service']['mailchimp']));
    }
}
