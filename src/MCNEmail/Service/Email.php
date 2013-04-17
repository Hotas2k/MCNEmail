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

namespace MCNEmail\Service;

use Locale;
use MCNStdlib\Interfaces\MailServiceInterface;
use Traversable;
use Zend\Mail\Message as MailMessage;
use Zend\Mail\Transport\TransportInterface;
use Zend\Validator\EmailAddress as EmailValidator;
use Zend\Log\Logger;
use MCNEmail\Options\EmailOptions;

/**
 * Class Email
 * @package MCNEmail\Service
 */
class Email implements MailServiceInterface
{
    /**
     * @var \MCNEmail\Options\EmailOptions
     */
    protected $options;

    /**
     * @var \MCNEmail\Service\TemplateInterface
     */
    protected $templates;

    /**
     * @var \Zend\Mail\Transport\TransportInterface
     */
    protected $transport;

    /**
     * @param TemplateInterface $templates
     * @param EmailOptions      $options
     */
    public function __construct(TemplateInterface $templates, EmailOptions $options = null)
    {
        $this->options   = ($options === null) ? new EmailOptions() : $options;
        $this->templates = $templates;
    }

    /**
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport = null)
    {
        $this->transport = $transport;
    }

    /**
     * Get the mail transport
     *
     * @return \Zend\Mail\Transport\TransportInterface
     */
    public function getTransport()
    {
        if ($this->transport === null) {

            $class = '\\Zend\Mail\Transport\\' . $this->options->getDefaultTransport();
            $this->transport = new $class();
        }

        return $this->transport;
    }

    /**
     * Email a person with a message from the given id
     *
     * @param string             $email
     * @param string             $templateId
     * @param array|\Traversable $params
     * @param null               $locale
     * @param string             $format
     *
     * @throws Exception\InvalidArgumentException
     * @return mixed
     */
    public function send($email, $templateId, $params = null, $locale = null, $format = self::FORMAT_HTML)
    {
        if ($params === null) {
            $params = array();
        } elseif ($params instanceof Traversable) {
            $params = iterator_to_array($params);
        }

        if (!is_array($params)) {

            throw new Exception\InvalidArgumentException(
                'Third argument params should be either null, array or traversable.'
            );
        }

        $locale = ($locale === null) ? Locale::getDefault() : $locale;

        if (!$this->templates->has($templateId, $locale)) {
            $this->templates->create($templateId, $params, $locale, $format);
        }

        list ($subject, $body) = $this->templates->render($templateId, $params, $locale, $format);

        $message = new MailMessage();
        $message->setEncoding($this->options->getEncoding());

        $message->setBody($body);
        $message->setSubject($subject);

        $message->setReplyTo($this->options->getReplyTo());
        $message->setFrom($this->options->getFrom());
        $message->setTo($email);
        $message->setBcc($this->options->getBcc());

        $this->getTransport()->send($message);
    }
}
