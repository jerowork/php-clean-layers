<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'tests']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PER' => true,
        'concat_space' => ['spacing' => 'one'],
        'void_return' => true,
        'declare_strict_types' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_order' => true,
        'phpdoc_align' => ['align' => 'left'],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => null,
            'import_functions' => null,
        ],
        'multiline_whitespace_before_semicolons' => false,
        'single_line_throw' => false,
        'binary_operator_spaces' => false,
    ])
    ->setFinder($finder);
