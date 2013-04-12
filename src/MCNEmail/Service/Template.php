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

use MCNStdlib\Interfaces\MailServiceInterface;

/**
 * Class Template
 * @package MCNEmail\Service
 */
class Template implements TemplateInterface
{
    /**
     * Render a template
     *
     * @param string                  $templateId
     * @param null|\Traversable|array $params
     * @param null|string             $locale
     * @param string                  $format
     *
     * @return string[]
     */
    public function render($templateId, $params = null, $locale = null, $format = MailServiceInterface::FORMAT_HTML)
    {
        // TODO: Implement render() method.
    }

    /**
     * @param string $templateId
     *
     * @return bool
     */
    public function has($templateId)
    {
        // TODO: Implement has() method.
    }

    /**
     * @param string                  $templateId
     * @param null|\Traversable|array $params
     *
     * @throws Exception\RuntimeException If the template already exists
     *
     * @return void
     */
    public function create($templateId, array $params = array())
    {
        // TODO: Implement create() method.
    }

    /**
     * Update the template parameters next time it's rendered
     *
     * The next time a template is rendered it should update the update the params.
     *
     * @param string $templateId
     *
     * @throws Exception\TemplateNotFoundException
     *
     * @return void
     */
    public function templateRequestNewParams($templateId)
    {
        // TODO: Implement templateRequestNewParams() method.
    }
}
