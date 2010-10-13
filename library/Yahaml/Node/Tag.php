<?php
namespace Yahaml\Node;

class Tag extends AbstractNode
{
    protected $_tag;
    protected $_text;
    protected $_attrs = array();
    
    public function __construct($text, $tag)
    {
        $this->_tag = $tag;
        $this->_parseText($text);
    }

    public function render($nested)
    {
        return '<' . $this->_tag . $this->_renderAttrs() . '>';
    }

    protected function _renderAttrs()
    {
        return '';
    }

    public function renderClose($nested)
    {
        return '</' . $this->_tag. '>';
    }

    protected function _parseText($text)
    {
        $classes = array();

        while (strlen($text) > 0) {
            switch ($text[0]) {
                case '#':
                    if (!preg_match('~^#([\w\-]+)~', $text, $matches)) {
                        throw new Exception("Cannot parse id");
                    }

                    $this->_attrs['id'] = $matches[1];
                    $text = substr($text, strlen($matches[0]));

                    break;

                case '.':
                    if (!preg_match('~^\.([\w\-]+)~', $text, $matches)) {
                        throw new Exception("Cannot parse class");
                    }

                    $class = array();

                    $class['name'] = $matches[1];
                    $text = substr($text, strlen($matches[0]));

                    if ($text[0] == '?') {
                        if (!preg_match('~^\?\(\s*(.*?)\s*\)~', $text, $matches)) {
                            throw new Exception("cannot parse class condition");
                        }

                        $class['condition'] = $matches[1];
                        $text = substr($text, strlen($matches[0]));
                    }

                    $classes[] = $class;

                    break;

                case '{':
                    if (!preg_match('~^\{(.*?)\}~', $text, $matches)) {
                        throw new Exception("Cannot parse custom token");
                    }

                    $this->_clt = $matches[1];
                    $text = substr($text, strlen($matches[0]));

                    break;

                case '[':
                    if (!preg_match('~^\[\s*(.*?)\s*\]~', $text, $matches)) {
                        throw new Exception("Cannot parse custom token");
                    }

                    $content = $matches[1];
                    $text = substr($text, strlen($matches[0]));
                    
                    while (preg_match('~([\w\-:]+)(?:\s*=\s*()~'))
                    
                    break;
                    
                default:
                    
            }
        }
    } 
}