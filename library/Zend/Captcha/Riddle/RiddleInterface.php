<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha\Riddle;

use Zend\Captcha\AdapterInterface;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
interface RiddleInterface extends AdapterInterface
{
    /**
     * @return string
     */
    public function getRiddle();
}
