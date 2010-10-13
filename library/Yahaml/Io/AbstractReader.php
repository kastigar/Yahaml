<?php
namespace Yahaml\Io;

abstract class AbstractReader implements Reader
{
    protected $_indentation;
    protected $_eod = false;

    /**
     * (non-PHPdoc)
     * @see Yahaml\Io\Reader::getNextLine()
     */
    public function getNextLine($forceLevel = null)
    {
        $line = $this->_getRawLine();

        if (!isset($line)) {
            if ($this->_eod) {
                return null;
            }

            $line = '';
            $this->_eod = true;
        }

        return new Line($line, $this, $forceLevel);
    }

    public function getIndentation()
    {
        return $this->_indentation;
    }

    public function setIndentation($indentation)
    {
        $this->_indentation = $indentation;
    }

    abstract protected function _getRawLine();
}