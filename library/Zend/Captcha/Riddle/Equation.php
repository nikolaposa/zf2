<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha\Riddle;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Equation extends BaseRiddle
{
    public static $ops = array("+", "-");
    
    /**
     * @type string
     */
    protected $label = 'Please solve this equation: %s';
    
    /**
     * @var string 
     */
    private $equation;

    /**
     * Generates required value for this CAPTCHA.
     * 
     * @return string
     */
    protected function generateCaptchaValue()
    {
        $operator = self::$ops[array_rand(self::$ops)];
        $number1 = mt_rand(1, 9);
        $number2 = mt_rand(1, 9);
        
        $result = 0;
        switch ($operator) {
            case '-' :
                if ($number2 > $number1) {
                    //avoid negative result
                    $temp1 = $number2;
                    $temp2 = $number1;
                    $number1 = $temp1;
                    $number2 = $temp2;
                } 
                $result = $number1 - $number2; 
                break;
            case '+' : 
            default :
                $result = $number1 + $number2;
        }
        
        
        $template = '%1$d %2$s %3$d';
        $this->equation = sprintf($template, $number1, $operator, $number2);
        
        return (string) $result;
    }

    public function getRiddle()
    {
        return sprintf(
            $this->getLabel(),
            $this->equation
        );
    }
}
