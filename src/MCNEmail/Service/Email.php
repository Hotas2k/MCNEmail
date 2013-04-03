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

use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Message as MailMessage;
use Zend\Mail\Transport\TransportInterface;
use Zend\Validator\EmailAddress as EmailValidator;
use Zend\Log\LoggerInterface;
use MCNEmail\Entity\Template  as TemplateEntity;
use MCNEmail\Service\Template as TemplateService;
use Zend\Log\Logger;
use MCNEmail\Options\EmailOptions;
use Mustache_Engine;

/**
 * @category MCNEmail
 * @package Service
 */
class Email
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EmailOptions
     */
    protected $options;

    /**
     * @var Template
     */
    protected $service;

    /**
     * @var \Zend\Mail\Transport\TransportInterface
     */
    protected $transport;

    /**
     * @var \Mustache_Engine
     */
    protected $mustache;

    /**
     * @param Template           $service
     * @param TransportInterface $transport
     * @param EmailOptions       $options
     */
    public function __construct(TemplateService $service, TransportInterface $transport, EmailOptions $options = null)
    {
        $this->options   = ($options == null) ? new EmailOptions() : $options;
        $this->service   = $service;
        $this->transport = $transport;
        $this->mustache  = new Mustache_Engine(array(
            'cache'   => 'data/tmp/',
            'charset' => 'utf-8'
        ));
    }

    /**
     * @param \Zend\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return \Zend\Log\LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger == null) {

            $this->logger = new Logger();
        }

        return $this->logger;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $email
     * @param array  $variables
     *
     * @return boolean
     */
    public function send($name, $description, $email, array $variables = array())
    {
        $template = $this->service->get($name);

        if (! $template) {

            $this->service->create($name, $description, $variables);

            $this->getLogger()->crit(
                sprintf('MCNEmail service: a new template was created name: %s, description: %s', $name, $description)
            );

            return false;
        }

        if (! $template->isValid()) {

            $this->getLogger()->emerg(
                sprintf('MCNEmail service: attempt to send email failed due to invalid template, name: %s', $name),
                array(
                    'email'     => $email,
                    'variables' => $variables
                )
            );

            return false;
        }

        // render
        $subject        = $this->mustache->render($template->getSubject(), $variables);
        $templateString = $this->mustache->render($template->getTemplate(), $variables);

        $message = $this->getBasicMessage();

        // TODO: find out why this is required
        // If it's not added then the html gets sent as an attachment
        $text = new MimePart('');
        $text->type = "text/plain";

        $html = new MimePart($templateString);
        $html->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts(array($html, $text));

        // Apply the variable stuff
        $message->setTo($email)
                ->setBody($body)
                ->setSubject($subject);

        // Get the emails
        $bcc = explode(',', str_replace(' ', '', $template->getBcc()));

        // Validator
        $validator = new EmailValidator();

        foreach($bcc as $email)
        {

            if ($validator->isValid($email)) {

                $message->addBcc($email);

            } else {

                $this->getLogger()->warn(
                    sprintf('MCNEmail service: invalid BCC address "%s" specified for template name: %s', $email, $name)
                );
            }
        }

        $this->transport->send($message);

        return true;
    }

    /**
     * @return \Zend\Mail\Message
     */
    public function getBasicMessage()
    {
        $message = new MailMessage();

        // Set the basic stuff
        $message->setFrom($this->options->from)
                ->setReplyTo($this->options->reply_to)
                ->setEncoding($this->options->encoding);

        return $message;
    }
}
