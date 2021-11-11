<?php

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(false)
    ->setRules(
        [
            /**
             * Rule sets
             */
            '@PSR1' => true,
            '@PSR2' => true,
            '@Symfony' => true, // Includes @PSR12

            /**
             * Array Notation
             */
            'array_syntax' => ['syntax' => 'short'],

            /**
             * Cast Notation
             */
            'cast_spaces' => ['space' => 'none'],

            /**
             * Class Notation
             */
            'protected_to_private' => false,

            /**
             * Control Structure
             */
            'yoda_style' => false,

            /**
             * Function Notation
             */
            'single_line_throw' => false,

            /**
             * Import
             */
            'ordered_imports' => [
                'sort_algorithm' => 'alpha',
                'imports_order' => ['class', 'const', 'function']
            ],

            /**
             * Language Construct
             */
            'declare_equal_normalize' => ['space' => 'single'],

            /**
             * List Notation
             */
            'list_syntax' => ['syntax' => 'short'],

            /**
             * Operator
             */
            'concat_space' => ['spacing' => 'one'],
            'increment_style' => ['style' => 'post'],

            /**
             * PHPDoc
             */
            'align_multiline_comment' => ['comment_type' => 'phpdocs_only'], // psr-5
            'no_superfluous_phpdoc_tags' => true,
            'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],
            'phpdoc_align' => false,
            'phpdoc_no_empty_return' => false,
            'phpdoc_no_useless_inheritdoc' => false,
            'phpdoc_order' => true, // psr-5
            'phpdoc_to_comment' => false,

            /**
             * Whitespace
             */
            'array_indentation' => true,
            'compact_nullable_typehint' => true,
        ]
    )
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/app')
            ->in(__DIR__ . '/tests')
            ->in(__DIR__ . '/public')
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );
