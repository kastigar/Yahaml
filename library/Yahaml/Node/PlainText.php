<?php
namespace Yahaml\Node;

class PlainText extends AbstractNode
{
    protected $_nestable = false;
    protected $_text;

    public function __construct($text, $escape)
    {
        $this->_text = $text;
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