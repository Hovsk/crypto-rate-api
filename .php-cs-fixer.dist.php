<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'phpdoc_align' => ['align' => 'left'],
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
