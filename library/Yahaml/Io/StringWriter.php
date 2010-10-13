<?php
namespace Yahaml\Io;

class StringWriter extends AbstractWriter
{
    protected $_text = '';
    
    public function __construct($indentation = null)
    {
        parent::__construct($indentation);
    }

    protected function _putRaw($text)
    {
        $this->_text .= $text;
    }

    public function getText()
    {
        return $this->_text;
    }
}