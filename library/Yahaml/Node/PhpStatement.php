<?php
namespace Yahaml\Node;

use \Yahaml\Io\Line;

class PhpStatement extends AbstractNode
{
    protected $_text;

    public function __construct(Line $line)
    {
        $this->_text = trim((string) $line);
    }

    public function render($nested)
    {
        return '<' . '?php ' . $this->_text . ' ?' . '>';
    }
}