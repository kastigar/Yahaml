<?php
namespace Yahaml\Node;

use \Yahaml\Io\Line;

class XmlComment extends AbstractNode
{
    protected $_text;

    public function __construct(Line $line)
    {
        $this->_text = trim((string) $line);
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