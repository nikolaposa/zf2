<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha;

use Zend\Captcha\Riddle\RiddleInterface;

/**
 * Example dumb word-based captcha
 *
 * Note that only rendering is necessary for word-based captcha
*/
class Dumb extends AbstractWord implements RiddleInterface
{
    /**
     * CAPTCHA label
     * @type string
     */
    protected $label = 'Please type this word backwards %s';

    /**
     * Set the label for the CAPTCHA
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Retrieve the label for the CAPTCHA
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Retrieve optional view helper name to use when rendering this captcha
     *
     * @return string
     */
    public function getHelperName()
    {
        return 'captcha/riddle';
    }

    /**
     * @return string
     */
    public function getRiddle()
    {
        return sprintf(
            $this->getLabel(),
            '<b>' . strrev($this->getWord()) . '</b>'
        );
    }
    
}
