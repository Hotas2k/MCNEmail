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

use Locale;
use MCNEmail\Service\Email;
use MCNEmail\Options\EmailOptions;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;

/**
 * Class EmailTest
 * @package MCNEmailTest\Service
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->options = new EmailOptions(
            array(
                'encoding' => 'utf-16',
                'from'    => 'test@cli.com',
                'reply_to' => 'webmaster@cli.net',
                'bcc'      => array('man-in-the-middle@attack.now')
            )
        );

        $this->transport       = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $this->templateService = $this->getMock('MCNEmail\Service\TemplateInterface');

        $this->service = new Email($this->templateService, $this->options);
        $this->service->setTransport($this->transport);
    }

    public function testGetTransport_DefaultAdapterSendMail()
    {
        $this->service->setTransport(null);
        $this->options->setDefaultTransport('Sendmail');
        $this->assertInstanceOf('Zend\Mail\Transport\Sendmail', $this->service->getTransport());
    }

    public function testGetTransport_SameInstanceFromSetTransport()
    {
        $transport = new Smtp();
        $this->service->setTransport($transport);
        $this->assertSame($transport, $this->service->getTransport());
    }

    /**
     * @expectedException \MCNEmail\Service\Exception\InvalidArgumentException
     */
    public function testSend_ThrowsExceptionOnInvalidParams()
    {
        $this->service->send('mail', 'test', 'params');
    }

    /**
     * @group params
     */
    public function testSend_HandlesParamsBeingAnIterator()
    {
        $params = new \ArrayIterator(array('hello', 'world'));

        $this->templateService
            ->expects($this->once())
            ->method('render')
            ->with('tpl', Locale::getDefault(), $this->callback(function($p) use($params) {

                $diff = array_diff($p, array('hello', 'world'));

                return empty($diff);
            }));

        $this->service->send('test', 'tpl', $params);
    }

    /**
     *
     */
    public function testSend_CreatesIfTemplateIsMissing()
    {
        $this->templateService
            ->expects($this->once())
            ->method('has')
            ->with('tpl')
            ->will($this->returnValue(false));

        $this->templateService
            ->expects($this->once())
            ->method('create')
            ->with('tpl', Locale::getDefault(), array(), 'html');

        $this->service->send('test', 'tpl');
    }

    public function testSend_AssertMailMessageProperties()
    {
        $this->templateService
            ->expects($this->once())
            ->method('render')
            ->will($this->returnValue(array('subject', 'body')));

        $this->transport
            ->expects($this->once())
            ->method('send')
            ->with($this->callback(function($msg) {
                /**
                 * @var $msg \Zend\Mail\Message
                 */
                $this->assertEquals($msg->getSubject(), 'subject');
                $this->assertEquals($msg->getBody(), 'body');
                $this->assertEquals($msg->getEncoding(), 'utf-16');

                $this->assertTrue($msg->getFrom()->has('test@cli.com'));
                $this->asserttrue($msg->getReplyTo()->has('webmaster@cli.net'));
                $this->assertTrue($msg->getBcc()->has('man-in-the-middle@attack.now'));

                return $msg instanceof Message;
            }));

        $this->service->send('dev@cli.net', 'tpl');
    }
}
