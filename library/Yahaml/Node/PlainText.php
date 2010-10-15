<?php
namespace Yahaml\Node;

use \Yahaml\Io\Line;

class PlainText extends AbstractNode
{
    protected $_nestable = false;
    protected $_text;

    public function __construct(Line $line, $escape = false)
    {
        $this->_text = (string) $line;
        $this->_escape = (boolean) $escape;
    }

    public function render($nested)
    {
        if ($this->_escape) {
            $text = '<' .'?php echo escape(' . "<<<'YAHAML_EOPT'\n"
                  . $this->_text . "\n"
                  . "YAHAML_EOPT\n"
                  . ') ?' . '>';
        } else {
            $text = $this->_text;
        }

        return $text;
    }
}