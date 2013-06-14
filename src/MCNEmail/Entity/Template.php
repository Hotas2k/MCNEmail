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

namespace MCNEmail\Entity;

use MCNStdlib\Object\Entity\AbstractEntity;
use MCNStdlib\Object\Entity\Behavior\TimestampableTrait;
use Zend\Form\Annotation as Form;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Template
 * @package MCNEmail\Entity
 *
 * @ORM\Table(name="mcn_email_templates")
 * @ORM\Entity(repositoryClass="MCNEmail\Repository\Template")
 * @ORM\HasLifecycleCallbacks
 *
 * @Form\Name("email.template")
 */
class Template extends AbstractEntity
{
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Form\Attributes({ "disabled" : "disabled" })
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(length=6, nullable=true)
     */
    protected $locale = null;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @Form\Exclude
     */
    protected $params;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Form\Type("Text")
     * @Form\Options({ "label": "Subject" })
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Form\Type("Textarea")
     * @Form\Options({ "label": "Description" })
     * @Form\Attributes({"class": "input-block-level", "rows": "10"})
     */
    protected $template;

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param array $variables
     */
    public function setParams(array $variables)
    {
        $this->params = $variables;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
