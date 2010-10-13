<?php
namespace Yahaml\Node;

class AutocloseTag extends Tag
{
    protected $_nestable = false;

    public function render($nested)
    {
        return '<' . $this->_tag . $this->_renderAttrs() . ' />';
    }

    public function renderClose($nested)
    {
        return null;
    }
}