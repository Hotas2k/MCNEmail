<?php
/**
 * Copyright (c) 2011-2013 Antoine Hedgecock.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
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
