<?php
namespace Yahaml\Node;

class PhpStatement extends AbstractNode
{
    protected $_text;

    public function __construct($text)
    {
        $this->_text = trim($text);
    }

    public function render($nested)
    {
        return '<' . '?php ' . $this->_text . ' ?' . '>';
    }
}