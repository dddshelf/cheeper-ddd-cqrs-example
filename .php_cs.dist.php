<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor'])
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR2' => true,
            'linebreak_after_opening_tag' => true,
            'non_printable_character' => true,
            'ordered_imports' => ['sort_algorithm' => 'alpha'],
            'dir_constant' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'visibility_required' => ['elements' => ['property', 'method', 'const']],
            'no_unused_imports' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
        ]
    )
    ->setFinder($finder)
;
