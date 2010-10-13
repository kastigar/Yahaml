<?php

namespace \Yahaml;

class Context
{
    public function __construct()
    {
    }
    
    public function render()
    {
        if ($this->inDevMode() && $this->isOutdated()) {
            $this->compile();
        }
        
        include $this->getScriptPath();
    }
    
    public function compile()
    {
        $compiler = new Compiler();
        
        $compiler->setReader(new File($this->getTemplatePath()));
        $compiler->setWriter(new File($this->getScriptPath()));
        $compiler->compile();
    }
}

