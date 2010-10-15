<?php
namespace Yahaml\Node;

use \Yahaml\Io\Line;

class EchoStatement extends AbstractNode
{
    protected $_nestable = false;
    protected $_text;

    public function __construct(Line $line, $escape)
    {
        $text = trim((string) $line);
        $text = rtrim($text, ';');

        if ($escape) {
            $text = 'escape(' . $text . ')';
        }

        $this->_text = $text;
    }

    public function render($nested)
    {
        return '<' . '?php echo ' . $this->_text . ' ?' . '>';
    }
}