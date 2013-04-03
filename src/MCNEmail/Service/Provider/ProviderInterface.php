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
