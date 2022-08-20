<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor'])
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR12' => true,
            '@PSR12:risky' => true,
            'linebreak_after_opening_tag' => true,
            'non_printable_character' => true,
            'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
            'no_useless_return' => true,
            'visibility_required' => ['elements' => ['property', 'method', 'const']],
            'no_unused_imports' => true,
            '@PHP81Migration' => true,
            'static_lambda' => true,
            'use_arrow_functions' => true,
            'void_return' => true,
        ]
    )
    ->setFinder($finder)
;
