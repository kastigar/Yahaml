<?php
namespace Yahaml\Io;

interface Reader
{
    /**
     * Return next line from input stream
     * 
     * @return Yahaml\Io\Line
     */
    public function getNextLine();

    public function getIndentation();

    public function setIndentation($indentation);
}