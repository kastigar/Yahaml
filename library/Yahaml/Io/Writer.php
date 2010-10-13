<?php
namespace Yahaml\Io;

interface Writer
{
    public function put($text);
    public function indent();
    public function unindent();
    public function newLine();
    public function setIndentation($indentation);
}