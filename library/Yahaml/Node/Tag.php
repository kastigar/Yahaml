<?php
namespace Yahaml\Node;

use \Yahaml\Io\Line;

class Tag extends AbstractNode
{
    protected $_tag;
    protected $_text;
    protected $_attrs = array();

    public function __construct(Line $line, $tag)
    {
        $this->_tag = $tag;
        $this->_parseText($line);
    }

    public function render($nested)
    {
        return '<' . $this->_tag . $this->_renderAttrs() . '>';
    }

    protected function _renderAttrs()
    {
        $string = '';

        foreach ($this->_attrs as $name => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $string .= ' ' . $name . '=' . $value;
        }

        return $string;
    }

    public function renderClose($nested)
    {
        return '</' . $this->_tag. '>';
    }

    protected function _parseText(Line $line)
    {
        $attrListParsed = false;

        while ($line->match('(#|\.)([\w\-:]+)')) {
            if ($line->matches[1] == '#') {
                $this->_attrs['id'] = $line->matches[2];
                continue;
            }

            $this->_parseClass($line);
        }

        while (true) {
            if (!isset($this->_clt) && $line->match('\{(.*?)\}')) {
                $this->_clt = $line->matches[1];
            } elseif (!$attrListParsed && $line->match('\[\s*')) {
                $this->_parseAttrList($line);
                $attrListParsed = true;
            } else {
                break;
            }
        }
    }

    protected function _parseClass(Line $line)
    {
        $class = $line->matches[2];

        if ($line->match('\|([\w\-:]+)')) {
            $else = $line->matches[1];
        }

        if ($line->match('\?\{\s*(.*?)\s*\}')) {
            $class = 'if (' . $line->matches[1] . ') echo ' . "'" . $class . "';";

            if (isset($else)) {
                $class .= ' else echo ' . "'" . $else . "';";
            }

            $class = '<' . '?php ' . $class . '?' . '>';
        } else if (isset($else)) {
            throw new Exception("Wrong class definition");
        }

        $this->_attrs['class'][] = $class;
    }

    protected function _parseAttrList(Line $line)
    {
        while (true) {
            if (!$line->match('([\w\-:]+)\s*')) {
                if ($line[0] == ']') {
                    $line->skip();
                    return;
                }

                throw new Exception('Eror in argument list parsing');
            }

            $name = $line->matches[1];

            if (!$line->match('=\s*')) {
                $this->_attrs[$name] = true;
                continue;
            }

            if (!$line->match('[\'"]')) {
                if (!$line->match('(.*?)\s*(\s|\])')) {
                    throw new Exception('Eror in argument list parsing');
                }

                $this->_attrs[$name] = $line->matches[1];

                if ($line->matches[2] == ']') {
                    return;
                } else {
                    continue;
                }
            }

            $quote = $line->matches[0];
            break;
        }
    }
}