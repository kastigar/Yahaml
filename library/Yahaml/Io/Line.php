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
     * @var string
     */
    private static $_indentation;

    public function __construct($rawString, $reader)
    {
        $this->indentLevel = $this->_getIndentLevel($rawString, $reader);
    }

    /**
     * @param offset
     * @return boolean
     */
    public function offsetExists ($offset)
    {
        return isset($this->_text[$offset]);
    }

    /**
     * @param offset
     * @return string
     */
    public function offsetGet ($offset)
    {
        return $this->_text[$offset];
    }

    /**
     * @param offset
     * @param value
     * @throws \Exception
     */
    public function offsetSet ($offset, $value)
    {
        throw new \Exception("\Yahaml\Strem\Line has read-only access.");
    }

    /**
     * @param offset
     * @throws \Exception
     */
    public function offsetUnset ($offset)
    {
        throw new \Exception("\Yahaml\Strem\Line has read-only access.");
    }

    /**
     * Return substing for text.
     * 
     * $line($start, $len) === substr($line->_text, $start, $len)
     * 
     * @param integer $start
     * @param integer $length
     * @return string
     */
    public function __invoke($start = 0, $length = null)
    {
        return isset($length) ? substr($this->_text, $start, $length)
                              : substr($this->_text, $start);
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