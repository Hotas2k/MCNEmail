<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Entity;

use Zend\Form\Annotation;
use Doctrine\ORM\Mapping as ORM;
use MCN\Object\Entity\Behavior;
use MCN\Object\Entity\AbstractEntity;

/**
 * @ORM\Table(name="mcn_email_templates", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_name", columns={ "name" })
 * })
 * @ORM\Entity(repositoryClass="MCN\Object\Entity\Repository")
 * @ORM\HasLifecycleCallbacks
 *
 * @Annotation\Name("email.template")
 */
class Template extends AbstractEntity
{
    use Behavior\TimestampableTrait;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @Annotation\Exclude
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Annotation\Attributes({ "disabled" : "disabled" })
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Annotation\AllowEmpty
     */
    protected $bcc;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @Annotation\Exclude
     */
    protected $variables;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $template = null;

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->subject !== null && $this->template !== null);
    }

    //<editor-fold desc="Getters & setters">
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
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

    /**
     * @param string $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @return string
     */
    public function getBcc()
    {
        return $this->bcc;
    }
    //</editor-fold>
}
