<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Captcha;

use Zend\Math\Rand;
use Zend\Session\Container;

/**
 * Abstract class for id/session-based captcha adapters.
 */
abstract class BaseAdapter extends AbstractAdapter
{
    /**
     * Random session ID
     *
     * @var string
     */
    protected $id;

    /**
     * Required CAPTCHA solution.
     *
     * @var string
     */
    protected $captchaValue;

    /**
     * Session
     *
     * @var Container
     */
    protected $session;

    /**
     * Class name for sessions
     *
     * @var string
     */
    protected $sessionClass = 'Zend\Session\Container';
    
    /**
     * @var string 
     */
    protected $sessionKey = 'captchaValue';

    /**
     * Session lifetime for the captcha data
     *
     * @var int
     */
    protected $timeout = 300;

    /**
     * Should generate() keep session or create a new one?
     *
     * @var bool
     */
    protected $keepSession = false;

    /**#@+
     * Error codes
     */
    const MISSING_VALUE = 'missingValue';
    const MISSING_ID    = 'missingID';
    const BAD_CAPTCHA   = 'badCaptcha';
    /**#@-*/

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::MISSING_VALUE => 'Empty captcha value',
        self::MISSING_ID    => 'Captcha ID field is missing',
        self::BAD_CAPTCHA   => 'Captcha value is wrong',
    );
    
    /**
     * Retrieve session class to utilize
     *
     * @return string
     */
    public function getSessionClass()
    {
        return $this->sessionClass;
    }

    /**
     * Set session class for persistence
     *
     * @param  string $sessionClass
     * @return BaseAdapter
     */
    public function setSessionClass($sessionClass)
    {
        $this->sessionClass = $sessionClass;
        return $this;
    }
    
    /**
     * Retrieve captcha ID
     *
     * @return string
     */
    public function getId()
    {
        if (null === $this->id) {
            $this->setId($this->generateRandomId());
        }
        return $this->id;
    }

    /**
     * Set captcha identifier
     *
     * @param string $id
     * @return BaseAdapter
     */
    protected function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set timeout for session token
     *
     * @param  int $ttl
     * @return BaseAdapter
     */
    public function setTimeout($ttl)
    {
        $this->timeout = (int) $ttl;
        return $this;
    }

    /**
     * Get session token timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Sets if session should be preserved on generate()
     *
     * @param bool $keepSession Should session be kept on generate()?
     * @return BaseAdapter
     */
    public function setKeepSession($keepSession)
    {
        $this->keepSession = $keepSession;
        return $this;
    }
    
    /**
     * Get session object
     *
     * @throws Exception\InvalidArgumentException
     * @return Container
     */
    public function getSession()
    {
        if (!isset($this->session) || (null === $this->session)) {
            $id = $this->getId();
            if (!class_exists($this->sessionClass)) {
                throw new Exception\InvalidArgumentException("Session class $this->sessionClass not found");
            }
            $this->session = new $this->sessionClass('Zend_Form_Captcha_' . $id);
            $this->session->setExpirationHops(1, null);
            $this->session->setExpirationSeconds($this->getTimeout());
        }
        return $this->session;
    }

    /**
     * Set session namespace object
     *
     * @param  Container $session
     * @return BaseAdapter
     */
    public function setSession(Container $session)
    {
        $this->session = $session;
        if ($session) {
            $this->keepSession = true;
        }
        return $this;
    }
    
    /**
     * Generate new session ID and new captcha value
     *
     * @return string session ID
     */
    public function generate()
    {
        if (!$this->keepSession) {
            $this->session = null;
        }
        $id = $this->generateRandomId();
        $this->setId($id);
        $value = $this->generateCaptchaValue();
        $this->setCaptchaValue($value);
        return $id;
    }

    /**
     * Generate a random identifier
     *
     * @return string
     */
    protected function generateRandomId()
    {
        return md5(Rand::getBytes(32));
    }
    
    /**
     * Generates required value for this CAPTCHA.
     * 
     * @return string
     */
    abstract protected function generateCaptchaValue();
    
    /**
     * Set captcha value
     *
     * @param  string $value
     * @return BaseAdapter
     */
    protected function setCaptchaValue($value)
    {
        $session = $this->getSession();
        $key = $this->sessionKey;
        $session->$key = $value;
        $this->captchaValue = $value;
        
        return $this;
    }
    
    /**
     * Get captcha value
     *
     * @return string
     */
    public function getCaptchaValue()
    {
        if (empty($this->captchaValue)) {
            $session = $this->getSession();
            $key = $this->sessionKey;
            $this->captchaValue  = $session->$key;
        }
        return $this->captchaValue;
    }
    
    /**
     * Validate captcha input
     *
     * @see    Zend\Validator\ValidatorInterface::isValid()
     * @param  mixed $value
     * @param  mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!is_array($value)) {
            if (!is_array($context)) {
                $this->error(self::MISSING_VALUE);
                return false;
            }
            $value = $context;
        }

        $name = $this->getName();

        if (isset($value[$name])) {
            $value = $value[$name];
        }

        if (!isset($value['input'])) {
            $this->error(self::MISSING_VALUE);
            return false;
        }
        $input = strtolower($value['input']);
        $this->setValue($input);

        if (!isset($value['id'])) {
            $this->error(self::MISSING_ID);
            return false;
        }

        $this->id = $value['id'];
        if ($input !== $this->getCaptchaValue()) {
            $this->error(self::BAD_CAPTCHA);
            return false;
        }

        return true;
    }
}
