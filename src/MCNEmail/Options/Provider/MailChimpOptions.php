<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Options\Provider;

use Zend\Stdlib\AbstractOptions;

/**
 * @category PMG
 * @package Service
 * @subpackage Email
 */
class MailChimpOptions extends AbstractOptions
{
    /**
     * String pattern of the api uri
     */
    const RAW_API_URI = 'http://%s.api.mailchimp.com/1.3/';

    /**
     * API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API uri
     *
     * @var string
     */
    protected $apiUri;

    /**
     * If we should notify the user that they have been added to a list
     *
     * @var bool
     */
    protected $send_welcome = false;

    /**
     * If we should confirm to the user that they have been unsubscribe
     *
     * @var bool
     */
    protected $send_goodbye = false;

    /**
     * If a notification should be sent to the creator of the list
     *
     * @var bool
     */
    protected $send_notification = false;

    /**
     * @var bool
     */
    protected $double_optin = false;

    /**
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        $exp = explode('-', $apiKey);
        $this->apiUri = sprintf(self::RAW_API_URI, $exp[1]);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param boolean $double_optin
     */
    public function setDoubleOptin($double_optin)
    {
        $this->double_optin = $double_optin;
    }

    /**
     * @return boolean
     */
    public function getDoubleOptin()
    {
        return $this->double_optin;
    }

    /**
     * @param boolean $send_goodbye
     */
    public function setSendGoodbye($send_goodbye)
    {
        $this->send_goodbye = $send_goodbye;
    }

    /**
     * @return boolean
     */
    public function getSendGoodbye()
    {
        return $this->send_goodbye;
    }

    /**
     * @param boolean $send_notification
     */
    public function setSendNotification($send_notification)
    {
        $this->send_notification = $send_notification;
    }

    /**
     * @return boolean
     */
    public function getSendNotification()
    {
        return $this->send_notification;
    }

    /**
     * @param boolean $send_welcome
     */
    public function setSendWelcome($send_welcome)
    {
        $this->send_welcome = $send_welcome;
    }

    /**
     * @return boolean
     */
    public function getSendWelcome()
    {
        return $this->send_welcome;
    }

    /**
     * @return string
     */
    public function getApiUri()
    {
        return $this->apiUri;
    }
}
