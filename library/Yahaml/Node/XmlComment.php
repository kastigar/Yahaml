<?php
namespace Yahaml\Node;

class XmlComment extends AbstractNode
{
    protected $_text;

    public function __construct($text)
    {
        $this->_text = trim($text);
    }

    public function render($nested)
    {
        return '<!-- ' . $this->_text;
    }

    public function renderClose($nested)
    {
        return $nested?'-->':' -->';
    }
}