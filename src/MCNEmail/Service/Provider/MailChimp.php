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

/**
 * @namespace
 */
namespace MCNEmail\Service\Provider;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use MCNEmail\Service\Exception;
use MCNEmail\Service\Exception\AlreadySubscribedException;
use MCNEmail\Service\Exception\InvalidEmailAddressException;
use MCNEmail\Options\Provider\MailChimpOptions;

/**
 * @category PMG
 * @package Service
 * @subpackage Email
 */
class MailChimp implements ProviderInterface
{
    const METHOD_LIST_LISTS         = 'lists';
    const METHOD_LIST_UPDATE_MEMBER = 'listUpdateMember';
    const METHOD_LIST_SUBSCRIBE     = 'listSubscribe';
    const METHOD_LIST_UNSUBSCRIBE   = 'listUnsubscribe';

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var MailchimpOptions
     */
    protected $options;

    /**
     * @param MailchimpOptions $options
     */
    public function __construct(MailchimpOptions $options = null)
    {
        // no need for multiple instances
        $this->client = new HttpClient();
        $this->client->setMethod(HttpRequest::METHOD_POST);

        // Set the configuration
        $this->setOptions($options == null ? new MailchimpOptions() : $options);
    }

    /**
     * Returns the current configuration
     *
     * @return MailChimpOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the configuration for the current Mailchimp instance
     *
     * @param MailchimpOptions $configuration
     *
     * @return Mailchimp
     */
    public function setOptions(MailchimpOptions $configuration)
    {
        $this->options = $configuration;

        return $this;
    }

    /**
     * Retrieves a list of the lists available to the current api key
     *
     * @return array
     */
    public function getLists()
    {
        return $this->queryApi(self::METHOD_LIST_LISTS);
    }

    /**
     * Subscribes a given email address to the given list
     *
     * @abstract
     * @param string $list
     * @param string $email
     * @param string $format
     * @param array  $meta
     * @return bool
     */
    public function subscribe($list, $email, $format = self::FORMAT_HTML, $meta = array())
    {
        return $this->queryApi(
            self::METHOD_LIST_SUBSCRIBE,
            array(
                'id'            => $list,
                'email_address' => $email,
                'merge_vars'    => $meta,
                'email_type'    => $format,
                'double_optin'  => $this->options->getDoubleOptin()
            )
        );
    }

    /**
     * Removes the given email address from the list
     *
     * @abstract
     * @param string $list
     * @param string $email
     * @return bool
     */
    public function unsubscribe($list, $email)
    {
        return $this->queryApi(
            self::METHOD_LIST_UNSUBSCRIBE,
            array(
                'id'            => $list,
                'email_address' => $email,
                'send_goodbye'  => $this->options->getSendGoodbye(),
                'send_notify'   => $this->options->getSendNotification()
            )
        );
    }

    /**
     * Updates an already existing member of a list with the given details
     *
     * @abstract
     * @param string $list
     * @param string $oldEmail
     * @param string $newEmail
     * @param string $format
     * @param array $meta
     *
     * @return bool
     */
    public function update($list, $oldEmail, $newEmail, $format = self::FORMAT_HTML, $meta = array())
    {
        return $this->queryApi(
            self::METHOD_LIST_UPDATE_MEMBER,
            array(
                'email_type'    => $format,
                'email_address' => $oldEmail,
                'merge_vars'    => array_merge(
                    array(
                        'NEW-EMAIL' => $newEmail
                    ),
                    $meta
                )
            )
        );
    }

    /**
     * Sends the query to the api and does som basic handling of the response.
     *
     * @param string $method
     * @param array  $data
     * @return mixed
     */
    protected function queryApi($method, array $data = array())
    {
        $this->client->setUri($this->options->getApiUri() . '?method=' . $method);

        $this->client->setRawBody(
            json_encode(
                array_merge(
                    array(
                        'apikey' => $this->options->getApiKey()
                    ),

                    $data
                )
            )
        );

        $result = $this->client->send();

        // Check if the result was true
        if ($result->getBody() == 'true') {

            return true;

        } else {

            // We received json error object
            $json = json_decode($result->getBody());

            // check if the response is type of an error
            if ($result->getHeaders()->get('X-mailchimp-api-error-code')) {

                $this->handleErrorCode($json->code, $json->error);
            }

            return $json;
        }
    }

    /**
     * Converts the given error code into an exception and passes it's message along
     *
     * @throws \MCNEmail\Service\Exception\LogicException
     * @throws \MCNEmail\Service\Exception\AlreadySubscribedException
     * @throws \MCNEmail\Service\Exception\InvalidEmailAddressException
     *
     * @param integer $code
     * @param string  $message
     * @return void
     */
    protected function handleErrorCode($code, $message)
    {
        switch($code)
        {
            case 214:
                throw new Exception\AlreadySubscribedException($message);
                break;

            case 502:
                throw new Exception\InvalidEmailAddressException($message);
                break;

            default:
                throw new Exception\LogicException(
                    sprintf('Unimplemented error code "%d" message "%s"', $code, $message)
                );
                break;
        }
    }
}
