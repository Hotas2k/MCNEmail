<?php
/**
 * @author Antoine Hegdecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail;

use Zend\Mvc\Controller\ControllerManager;

/**
 * Class Module
 * @package MCNEmail
 */
class Module
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include_once __DIR__ . '/config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'mcn.service.email.template' => function ($sm) {

                    return new Service\Template(
                        $sm->get('doctrine.entitymanager.ormdefault')
                    );
                }
            )
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return array(
            'factories' => array(

                'mcn.email.template' => function (ControllerManager $sm) {

                    return new Controller\TemplateController(
                        $sm->getServiceLocator()->get('mcn.service.email.template')
                    );
                }
            )
        );
    }
}
