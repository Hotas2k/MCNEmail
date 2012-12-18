<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Service\Provider;

/**
 * @category PMG
 * @package Service
 * @subpackage Email
 */
interface ProviderInterface
{
    /**
     * User would like to receive the mails in a html format
     */
    const FORMAT_HTML = 'html';

    /**
     * User would like to receive the mails in a text format
     */
    const FORMAT_TEXT = 'text';

    /**
     * User would like to receive the mails in a mobile format
     */
    const FORMAT_MOBILE = 'mobile';

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
    public function subscribe($list, $email, $format = self::FORMAT_HTML, $meta = array());

    /**
     * Removes the given email address from the list
     *
     * @abstract
     * @param string $list
     * @param string $email
     * @return bool
     */
    public function unsubscribe($list, $email);

    /**
     * Updates an already existing member of a list with the given details
     *
     * @abstract
     * @param string $list
     * @param string $oldEmail
     * @param string $newEmail
     * @param string $format
     * @param array  $meta
     *
     * @return bool
     */
    public function update($list, $oldEmail, $newEmail, $format = self::FORMAT_HTML, $meta = array());
}
