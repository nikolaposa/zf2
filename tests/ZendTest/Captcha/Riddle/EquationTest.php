<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Captcha\Riddle;

/**
 * @group      Zend_Captcha
 * @group      Zend_Captcha_Riddle
 */
class EquationTest extends CommonRiddleTest
{
    protected $riddleClass = 'Zend\Captcha\Riddle\Equation';

    public function testDefaultLabelIsUsedWhenNoAlternateLabelSet()
    {
        $this->assertEquals('Please solve this equation: %s', $this->captcha->getLabel());
    }

    public function testChangeLabelViaSetterMethod()
    {
        $this->captcha->setLabel('Testing');
        $this->assertEquals('Testing', $this->captcha->getLabel());
    }
    
    public function testGenerateRandomEquation()
    {
        $this->captcha->generate();
        
        $this->assertRegexp('/\d (\+|\-) \d/', $this->captcha->getRiddle());
        $this->assertTrue(is_numeric($this->captcha->getCaptchaValue()));
    }
}
