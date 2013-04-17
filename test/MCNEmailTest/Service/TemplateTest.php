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

namespace MCNEmailTest\Service;

use MCNEmail\Service\Template;
use PHPUnit_Framework_TestCase;

/**
 * Class TemplateTest
 * @package MCNEmailTest\Service
 */
class TemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $engine;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectRepository;

    /**
     * @var \MCNEmail\Service\Template
     */
    protected $service;

    protected function setUp()
    {
        $this->engine           = $this->getMock('MCNEmail\Service\Template\EngineInterface');
        $this->objectManager    = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectRepository = $this->getMock('MCNEmail\Repository\TemplateInterface');

        $this->objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->objectRepository));

        $this->service = new Template($this->objectManager, $this->engine);
    }

    public function testHas_returnsSameValueAsRepo()
    {
        $this->objectRepository
            ->expects($this->at(0))
            ->method('has')
            ->with(1, 'en_US')
            ->will($this->returnValue(false));

        $this->objectRepository
            ->expects($this->at(1))
            ->method('has')
            ->with(2, 'en_US')
            ->will($this->returnValue(true));

        $this->assertFalse($this->service->has(1, 'en_US'));
        $this->assertTrue($this->service->has(2, 'en_US'));
    }

    /**
     * @expectedException \MCNEmail\Service\Exception\TemplateNotFoundException
     */
    public function testRender_ThrowsTemplateNotFoundOnMissingTemplate()
    {
        $this->objectRepository
            ->expects($this->once())
            ->method('get');

        $this->service->render('id', 'en_US');
    }

    public function testRender_CallsRenderWithParams()
    {
        $params = array('foo' => 'bar');
        $template = new \MCNEmail\Entity\Template();
        $template->fromArray(
            array(
                'subject' => 'subject',
                'template' => 'template'
            )
        );

        $this->objectRepository
            ->expects($this->once())
            ->method('get')
            ->with('id', 'en_US', 'html')
            ->will($this->returnValue($template));

        $this->engine
            ->expects($this->at(0))
            ->method('render')
            ->with('subject', $params);

        $this->engine
            ->expects($this->at(1))
            ->method('render')
            ->with('template', $params);

        $this->service->render('id', 'en_US', $params);
    }

    public function testCreate_Success()
    {
        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function($entity) {

                $this->assertEquals('id', $entity->getId());
                $this->assertEquals('en_US', $entity->getLocale());
                $this->assertEquals(array('foo'), $entity->getParams());

                return true;
            }));


        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->service->create('id', 'en_US', array('foo'));
    }
}
