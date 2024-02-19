<?php

$finder = PhpCsFixer\Finder::create()
        ->in(__DIR__.'/{Controller,DependencyInjection,Entity,Form,Resources,Service}/')
;

$header =<<<'HEADER'
This file is part of itk-dev/config-bundle.

(c) 2018–2024 ITK Development

This source file is subject to the MIT license.
HEADER;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'header_comment' => ['header' => $header],
        'heredoc_to_nowdoc' => true,
    ])
    ->setFinder($finder)
;
