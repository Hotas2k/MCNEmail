<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
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
     * @param Template           $service
     * @param TransportInterface $transport
     * @param EmailOptions       $options
     */
    public function __construct(TemplateService $service, TransportInterface $transport, EmailOptions $options = null)
    {
        $this->options   = ($options == null) ? new EmailOptions() : $options;
        $this->service   = $service;
        $this->transport = $transport;
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

        // Place the variables in the template
        $templateString = $template->render($variables);

        // Create the message
        $message = $this->getBasicMessage();

        // TODO: find out why this is required
        // If it's not added then the html gets sent as an attachment
        $text = new MimePart('');
        $text->type = "text/plain";

        $html = new MimePart($templateString);
        $html->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        // Apply the variable stuff
        $message->setTo($email)
                ->setBody($body)
                ->setSubject($template->getSubject());

        // Get the emails
        $bcc = explode(',', str_replace(' ', '', $template->getBcc()));

        // Validator
        $validator = new EmailValidator();

        foreach($bcc as $email)
        {
            if (! $validator->isValid($email)) {

                $this->getLogger()->warn(
                    sprintf('MCNEmail service: invalid BCC address "%s" specified for template, name: %s', $email, $name)
                );
            }

            $message->addBcc($email);
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
