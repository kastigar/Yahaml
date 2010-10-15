<?php
namespace Yahaml;

use Exception,
    Yahaml\Io\Line,
    Yahaml\Io\Reader,
    Yahaml\Io\Writer,
    Yahaml\Io\StringWriter;

class Compiler
{
    /**
     * Enter description here ...
     * 
     * @var Yahaml\Configuration
     */
    private $_config;

    /**
     * Enter description here ...
     * 
     * @var Yahaml\Io\Reader
     */
    private $_reader;

    /**
     * Enter description here ...
     * 
     * @var Yahaml\Io\Writer
     */
    private $_writer;

    /**
     * Enter description here ...
     * 
     * @param Yahaml\Configuration $config
     * @return void
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * Returns Reader instance
     * 
     * @return Yahaml\Io\Reader
     */
    public function getReader()
    {
        return $this->_reader;
    }

    /**
     * Set reader instance
     * 
     * @param Yahaml\Io\Reader $reader
     * @return void
     */
    public function setReader(Reader $reader)
    {
        $this->_reader = $reader;
    }

    public function getWriter()
    {
        if (!isset($this->_writer)) {
            $this->_writer = new StringWriter;
        }

        return $this->_writer;
    }

    public function setWriter(Writer $writer)
    {
        $this->_writer = $writer;
    }

    /**
     * Compile template
     * 
     * @return void
     * @throws Exception
     */
    public function compile()
    {
        $this->_checkEnvironment();

        $reader = $this->getReader();
        $writer = $this->getWriter();

        $line = $reader->getNextLine();
        $closeStack = array();
        $escape = true;

        if ($line->indentLevel > 0) {
            throw new Exception("Indenting at the beginning of the document is illegal.");
        }

        while ($nextLine = $reader->getNextLine()) {
            switch ($line[0]) {
                case '%':
                case '#':
                case '.':
                    $node = $this->_parseTag($line);
                    break;

                case '/':
                    $node = new Node\XmlComment($line->skip());
                    break;

                case '=':
                    $node = new Node\EchoStatement($line->skip(), $escape);
                    break;

                case '-':
                    $node = new Node\PhpStatement($line->skip());
                    break;

                case '&':
                    if ($line->match('&==')) {
                        $node = new Node\PlainText($line, true);
                    } elseif ($line[1] == '=') {
                        $node = new Node\EchoStatement($line->skip(2), true);
                    } elseif ($line[1] == ' ') {
                        $node = new Node\PlainText($line->skip(2), true);
                    } else {
                        $node = new Node\PlainText($line);
                    }

                    break;

                case '!':
                    if ($line->match('!!!')) {
                        $node = new Node\Doctype($line);
                    } elseif ($line->match('!==')) {
                        $node = new Node\PlainText($line, false);
                    } elseif ($line[1] == '=') {
                        $node = new Node\EchoStatement($line->skip(2), false);
                    } elseif ($line[1] == ' ') {
                        $node = new Node\PlainText($line->skip(2), false);
                    } else {
                        $node = new Node\PlainText($line);
                    }

                    break;

                case '\\':
                    $node = new Node\PlainText($line->skip(), $escape);
                    break;

                case ':':
                    $node = new Node\PlainText($line->skip(), $escape);
                    break;

                default:
                    $node = new Node\PlainText($line, $escape);
            }

            $levelDiff = $nextLine->indentLevel - $line->indentLevel;

            if (!$node->isNestable() && $levelDiff > 0) {
                throw new Exception("Line can't be indented");
            } elseif ($levelDiff > 1) {
                throw new Exception("Too long indentation");
            }

            $writer->put($node->render($levelDiff == 1));
            $close = $node->renderClose($levelDiff == 1);

            if ($levelDiff <= 0) {
                $writer->put($close);

                if ($levelDiff < 0) {
                    for ($i = 0; $i < (-$levelDiff); $i++) {
                        $close = array_pop($closeStack);
                        $writer->unindent();
                        $writer->newLine();
                        $writer->put($close);
                    }
                }
            } else {
                $writer->indent();
                array_push($closeStack, $close);
            }

            $line = $nextLine;
            $writer->newLine();
        }
    }

    protected function _parseTag(Line $line)
    {
        $tag = $line->match('%([\w\-:]+)') ? $line->matches[1] : 'div';
        $class = $this->_findTagClass($tag);

        return new $class($line, $tag);
    }

    protected function _findTagClass($tag)
    {
        static $autoclose = array('meta', 'img', 'link', 'br', 'hr', 'input', 'area', 'param', 'col', 'base');

        // find own class in namespaces

        return '\Yahaml\Node\\' . (in_array($tag, $autoclose) ? 'AutocloseTag' : 'Tag');
    }

    /**
     * Check all required things
     * 
     * @return void
     * @throws Exception
     */
    protected function _checkEnvironment()
    {
        if (is_null($this->getReader())) {
            throw new Exception("Reader is not defined");
        }
    }
}