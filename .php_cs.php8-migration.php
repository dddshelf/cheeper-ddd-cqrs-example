<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor'])
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
        ]
    )
    ->setFinder($finder)
;
