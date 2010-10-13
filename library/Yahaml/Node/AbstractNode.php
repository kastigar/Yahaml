<?php
namespace Yahaml\Node;

abstract class AbstractNode
{
    protected $_nestable = true;

    abstract public function render($nested);

    public function renderClose($nested)
    {
        return null;
    }

    public function isNestable()
    {
        return $this->_nestable;
    }
}