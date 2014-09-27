<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha;

/**
 * AbstractWord-based captcha adapter
 *
 * Generates random word which user should recognise
 */
abstract class AbstractWord extends BaseAdapter
{
    /**#@+
     * @var array Character sets
     */
    public static $V  = array("a", "e", "i", "o", "u", "y");
    public static $VN = array("a", "e", "i", "o", "u", "y","2","3","4","5","6","7","8","9");
    public static $C  = array("b","c","d","f","g","h","j","k","m","n","p","q","r","s","t","u","v","w","x","z");
    public static $CN = array("b","c","d","f","g","h","j","k","m","n","p","q","r","s","t","u","v","w","x","z","2","3","4","5","6","7","8","9");
    /**#@-*/

    /**
     * @var string 
     */
    protected $sessionKey = 'word';
    
    /**
     * Should the numbers be used or only letters
     *
     * @var bool
     */
    protected $useNumbers = true;

    /**
     * Should both cases be used or only lowercase
     *
     * @var bool
     */
    // protected $useCase = false;

    /**
     * Length of the word to generate
     *
     * @var int
     */
    protected $wordlen = 8;

    /**
     * Retrieve word length to use when generating captcha
     *
     * @return int
     */
    public function getWordlen()
    {
        return $this->wordlen;
    }

    /**
     * Set word length of captcha
     *
     * @param int $wordlen
     * @return AbstractWord
     */
    public function setWordlen($wordlen)
    {
        $this->wordlen = $wordlen;
        return $this;
    }

    /**
     * Numbers should be included in the pattern?
     *
     * @return bool
     */
    public function getUseNumbers()
    {
        return $this->useNumbers;
    }

    /**
     * Set if numbers should be included in the pattern
     *
     * @param  bool $useNumbers numbers should be included in the pattern?
     * @return AbstractWord
     */
    public function setUseNumbers($useNumbers)
    {
        $this->useNumbers = $useNumbers;
        return $this;
    }

    /**
     * Get captcha word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->getCaptchaValue();
    }

    /**
     * Generates required value for this CAPTCHA.
     * 
     * @return string
     */
    protected function generateCaptchaValue()
    {
        $word       = '';
        $wordLen    = $this->getWordLen();
        $vowels     = $this->useNumbers ? static::$VN : static::$V;
        $consonants = $this->useNumbers ? static::$CN : static::$C;

        for ($i=0; $i < $wordLen; $i = $i + 2) {
            // generate word with mix of vowels and consonants
            $consonant = $consonants[array_rand($consonants)];
            $vowel     = $vowels[array_rand($vowels)];
            $word     .= $consonant . $vowel;
        }

        if (strlen($word) > $wordLen) {
            $word = substr($word, 0, $wordLen);
        }

        return $word;
    }

    /**
     * Get helper name used to render captcha
     *
     * @return string
     */
    public function getHelperName()
    {
        return 'captcha/word';
    }
}
