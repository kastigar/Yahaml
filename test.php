<?php

set_include_path(realpath("./library") . PATH_SEPARATOR . get_include_path());

spl_autoload_register(function($className) {
    $path = str_replace('\\', '/', $className) . '.php';
    require_once $path;
    
    return true;
});

$template = <<<'EOL'
!!!
%html
    %head
    %body
        .header
            %img
            %br
        .content
            - $a = "string"
            .rounded
            #ided
            #merged.class
            .class.class2
            .class?{$a == 'string'}
            .class|class2?{$a == 'string2'}
            / nested comment
                aaaaaa
                bbbbbb
                %p
                    test
                = "ccccc"
            %input[type=text value=test]
            = "Test<b>b</b>"
            &= "Test<b>b</b>"
            != "Test<b>b</b>"
            Raw <i>Line</i>
            / inline comment
            = $a
        .footer
EOL;

use Yahaml\Compiler,
    Yahaml\Io\StringReader;

$compiler = new Compiler(null);
$compiler->setReader(new StringReader($template));
$compiler->compile();

$code = $compiler->getWriter()->getText();

var_dump($code);

function escape($string) {
    return htmlspecialchars($string);
}

ob_start();
eval('?' . '>' . $code);
$out = ob_get_clean();

var_dump($out);