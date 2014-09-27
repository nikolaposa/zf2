<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha\Riddle;

use Zend\Captcha\BaseAdapter;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class BaseRiddle extends BaseAdapter implements RiddleInterface
{
    /**
     * Label, riddle problem.
     * 
     * @type string
     */
    protected $label;

    /**
     * Set riddle's label.
     * 
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get riddle's label.
     * 
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
}
