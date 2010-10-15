<?php
namespace Yahaml\Io;

class Line implements \ArrayAccess
{
    /**
     * Indentation level
     * 
     * @var integer
     */
    public $indentLevel;

    /**
     * Enter description here ...
     * 
     * @var array
     */
    public $matches;

    /**
     * Enter description here ...
     * 
     * @var string
     */
    protected static $_indentation;

    /**
     * Text from current position
     * 
     * @var string
     */
    protected $_text;

    public function __construct($rawString, $reader)
    {
        $this->indentLevel = $this->_getIndentLevel($rawString, $reader);
    }

    /**
     * @param offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_text[$offset]);
    }

    /**
     * @param offset
     * @return string
     */
    public function offsetGet($offset)
    {
        if (strlen($this->_text) == 0) debug_print_backtrace();
        return $this->_text[$offset];
    }

    /**
     * @param offset
     * @param value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("\Yahaml\Strem\Line has read-only access.");
    }

    /**
     * @param offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("\Yahaml\Strem\Line has read-only access.");
    }

    /**
     * Enter description here ...
     * 
     * @param string $pattern
     * @param string $token
     */
    public function match($pattern, $token = '~')
    {
        if (!preg_match($token . '^' . $pattern . $token, $this->_text, $this->matches)) {
            return false;
        }

        $this->skip(strlen($this->matches[0]));

        return true;
    }

    /**
     * Enter description here ...
     * 
     * @param integer $len
     * @return Yahaml\Io\Line
     */
    public function skip($len = 1)
    {
        if (strlen($this->_text) > $len) {
            $this->_text = substr($this->_text, $len);
        } else {
            $this->_text = '';
        }

        return $this;
    }

    public function __toString()
    {
        return $this->_text;
    }

    protected function _getIndentLevel($rawString, $reader)
    {
        if (empty($rawString) || !preg_match('~^\s+~', $rawString, $matches)) {
            $this->_text = $rawString;

            return 0;
        }

        $lineIndentation = $matches[0];

        if (!($standardIndentation = $reader->getIndentation())) {
            $reader->setIndentation($lineIndentation);
            $this->_text = substr($rawString, strlen($lineIndentation));

            return 1;
        }

        $level = floor(strlen($lineIndentation)/strlen($standardIndentation));
        $indentation = str_repeat($standardIndentation, $level);

        if (strpos($lineIndentation, $indentation) !== 0) {
            throw new Exception("Wrong indentation");
        }

        $this->_text = substr($rawString, strlen($indentation));

        return $level;
    }
}