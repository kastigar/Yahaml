<?php
namespace Yahaml\Node;

class EchoStatement extends AbstractNode
{
    protected $_nestable = false;
    protected $_text;
    protected $_escape;

    public function __construct($text, $escape)
    {
        $this->_text = trim($text);
        $this->_escape = (boolean) $escape;
    }

    public function render($nested)
    {
        if ($this->_escape) {
            $text = 'escape(' . rtrim($this->_text, ';') . ')';
        } else {
            $text = $this->_text;
        }

        return '<' . '?php echo ' . $text . ' ?' . '>';
    }
}