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
abstract class CommonRiddleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Captcha riddle adapter class name
     *
     * @var string
     */
    protected $riddleClass;
    
    public function setUp()
    {
        $this->captcha = new $this->riddleClass(array(
            'sessionClass' => 'ZendTest\Captcha\TestAsset\SessionContainer',
        ));
    }

    public function testSetLabel()
    {
        $label = 'Test';
        $this->captcha->setLabel($label);
        $this->assertEquals($label, $this->captcha->getLabel());
    }
    
    public function testUsesCaptchaRiddleAsHelper()
    {
        $this->assertEquals('captcha/riddle', $this->captcha->getHelperName());
    }
    
    public function testGeneratePopulatesId()
    {
        $id   = $this->captcha->generate();
        $test = $this->captcha->getId();
        $this->assertEquals($id, $test);
    }
}
