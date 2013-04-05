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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager,
    MCNEmail\Entity\Template as TemplateEntity;

/**
 * Class Template
 * @package MCNEmail\Service
 */
class Template
{
    const SORT_EMPTY_TEMPLATE = 'empty_template';

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->objectManager = $em;
    }

    /**
     * @return \MCNEmail\Repository\TemplateInterface
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository('MCNEmail\Entity\Template');
    }

    /**
     * @param string $name
     * @return TemplateEntity|null
     */
    public function get($name)
    {
        return $this->getRepository()->getByName($name);
    }

    /**
     * @param integer $id
     *
     * @return TemplateEntity
     */
    public function getById($id)
    {
        return $this->getRepository()->fetchById($id);
    }

    /**
     * @param \MCNEmail\Entity\Template $entity
     */
    public function save(TemplateEntity $entity)
    {
        if (! $this->objectManager->contains($entity)) {

            $this->objectManager->persist($entity);
        }

        // TinyMCE removes html and body tags
        $entity->setTemplate(sprintf('<html><body>%s</body></html>', $entity->getTemplate()));
        $this->objectManager->flush();
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return void
     */
    public function create($name, array $params)
    {
        $entity = new TemplateEntity();
        $entity->fromArray(
            array(
                'name'      => $name,
                'variables' => $params
            )
        );

        $this->objectManager->persist($entity);
        $this->objectManager->flush();
    }


    /**
     * @param array $options
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetchAll(array $options)
    {
        return $this->getRepository()->fetchAll($options);
    }
}
