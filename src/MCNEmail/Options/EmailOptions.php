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

namespace MCNEmail\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * @property $from            string
 * @property $reply_to        string
 * @property $encoding        string
 */
class EmailOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $bcc = array();

    /**
     * @var string
     */
    protected $default_transport = 'Sendmail';

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $reply_to;

    /**
     * @var string
     */
    protected $encoding = 'utf-8';

    /**
     * @var string
     */
    protected $engine = 'Twig';

    /**
     * @var array
     */
    protected $engine_options = array();

    /**
     * @param $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param $engine_options
     */
    public function setEngineOptions($engine_options)
    {
        $this->engine_options = $engine_options;
    }

    /**
     * @return array
     */
    public function getEngineOptions()
    {
        return $this->engine_options;
    }

    /**
     * @param array $bcc
     */
    public function setBcc(array $bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string $default_transport
     */
    public function setDefaultTransport($default_transport)
    {
        $this->default_transport = $default_transport;
    }

    /**
     * @return string
     */
    public function getDefaultTransport()
    {
        return $this->default_transport;
    }

    /**
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $reply_to
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->reply_to;
    }
}
