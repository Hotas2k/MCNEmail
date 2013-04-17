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

use MCNEmail\Entity\Template as TemplateEntity;
use Doctrine\Common\Persistence\ObjectManager;
use MCNEmail\Service\Template\EngineInterface;
use MCNStdlib\Interfaces\MailServiceInterface;
use Traversable;
use Twig_Environment;

/**
 * Class Template
 * @package MCNEmail\Service
 */
class Template implements TemplateInterface
{
    /**
     * @var Template\EngineInterface
     */
    protected $engine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManager            $objectManager
     * @param Template\EngineInterface $engine
     */
    public function __construct(ObjectManager $objectManager, EngineInterface $engine)
    {
        $this->engine        = $engine;
        $this->objectManager = $objectManager;
    }

    /**
     * @return \MCNEmail\Repository\TemplateInterface
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository('MCNEmail\Entity\Template');
    }

    /**
     * @inheritdoc
     */
    public function render($templateId, $locale, $params = null, $format = MailServiceInterface::FORMAT_HTML)
    {
        $template = $this->getRepository()->get($templateId, $locale, $format);

        if (! $template) {

            throw new Exception\TemplateNotFoundException;
        }

        return array(
            'subject' => $this->engine->render($template->getSubject(), $params),
            'body'    => $this->engine->render($template->getTemplate(), $params)
        );
    }

    /**
     * @inheritdoc
     */
    public function has($templateId, $locale)
    {
        return $this->getRepository()->has($templateId, $locale);
    }

    /**
     * @inheritdoc
     */
    public function create($templateId, $locale, $params = null, $format = MailServiceInterface::FORMAT_HTML)
    {
        if ($params instanceof Traversable) {
            $params = iterator_to_array($params);
        }

        if (! is_array($params)) {
            throw new Exception\InvalidArgumentException(
                'Second parameter params should be null, array or an instance of \Traversable'
            );
        }

        $entity = new TemplateEntity();
        $entity->fromArray(
            array(
                'id'     => $templateId,
                'params' => $params,
                'locale' => $locale
            )
        );

        $this->objectManager->persist($entity);
        $this->objectManager->flush($entity);

        return $entity;
    }
}
