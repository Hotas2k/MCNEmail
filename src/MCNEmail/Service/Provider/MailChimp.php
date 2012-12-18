<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
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
