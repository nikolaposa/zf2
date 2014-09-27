<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Form\View\Helper\Captcha;

use Zend\Captcha\Riddle\RiddleInterface as RiddleCaptchaAdapter;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class Riddle extends AbstractWord
{
    /**
     * Render the captcha
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $captcha = $element->getCaptcha();

        if ($captcha === null || !$captcha instanceof RiddleCaptchaAdapter) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has a "captcha" attribute of type Zend\Captcha\Riddle\RiddleInterface; none found',
                __METHOD__
            ));
        }

        $captcha->generate();
        
        if (null !== ($translator = $this->getTranslator())) {
            $captcha->setLabel($translator->translate(
                $captcha->getLabel(), $this->getTranslatorTextDomain()
            ));
        }

        $label = $captcha->getRiddle();

        $position     = $this->getCaptchaPosition();
        $separator    = $this->getSeparator();
        $captchaInput = $this->renderCaptchaInputs($element);

        $pattern = '%s%s%s';
        if ($position === self::CAPTCHA_PREPEND) {
            return sprintf($pattern, $captchaInput, $separator, $label);
        }

        return sprintf($pattern, $label, $separator, $captchaInput);
    }
}
