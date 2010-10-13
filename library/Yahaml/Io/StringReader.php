<?php
namespace Yahaml\Io;

class StringReader extends AbstractReader
{
    protected $_lines;
    protected $_index;

    public function __construct($string)
    {
        $this->_lines = explode("\n", $string);
        $this->_index = 0;
        $this->_count = count($this->_lines);
    }

    protected function _getRawLine()
    {
        if ($this->_index >= $this->_count) {
            return null;
        }

        return $this->_lines[$this->_index++];
    }
}