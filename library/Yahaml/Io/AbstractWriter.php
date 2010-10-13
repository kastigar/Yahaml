<?php
namespace Yahaml\Io;

abstract class AbstractWriter implements Writer
{
    protected $_indentLevel;
    protected $_indentation;

    abstract protected function _putRaw($text);
    
    public function __construct($indentation = null)
    {
        $this->_indentLevel = 0;
        $this->_indentation = $indentation ?: '    ';
    }

    public function put($text)
    {
        if (isset($text)) {
            $this->_putRaw($text);
        }
    }

    public function getIndentation()
    {
        return $this->_indentation;
    }

    public function indent()
    {
        $this->_indentLevel++;
    }

    public function unindent()
    {
        $this->_indentLevel--;
    }

    public function newLine()
    {
        $this->_putRaw("\n" . str_repeat($this->_indentation, $this->_indentLevel));
    }

    public function setIndentation($indentation)
    {
        $this->_indentation = $indentation;
    }

}