<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Factory\Provider;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MCNEmail\Factory\Exception;
use MCNEmail\Service\Provider\MailChimp as MailChimpService;
use MCNEmail\Options\Provider\MailChimpOptions;

/**
 *
 */
class MailChimp implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['MCNEmail'];

        if (! isSet($config['service']['mail-chimp'])) {

            throw new Exception\InvalidArgumentException(
                sprintf('No configuration was specified for mailchimp')
            );
        }

        return new MailchimpService(new MailChimpOptions($config['service']['mail-chimp']));
    }
}
