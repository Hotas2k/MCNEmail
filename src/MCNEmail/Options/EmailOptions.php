<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * @property $from            string
 * @property $reply_to        string
 * @property $encoding        string
 * @property $template_format string
 */
class EmailOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $from     = 'info@pmg.se';
    /**
     * @var string
     */
    protected $reply_to = 'info@pmg.se';
    /**
     * @var string
     */
    protected $encoding = 'utf-8';

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
