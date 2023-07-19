<?php

/*
 * This file is part of james.xue/sms-bombing.
 *
 * (c) xiaoxuan6 <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

$header = <<<HEADER
This file is part of james.xue/sms-bombing.

(c) xiaoxuan6 <15227736751@qq.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.

HEADER;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/bin',
    ])
    ->exclude([
        __DIR__ . '/vendor',
    ])
    ->name('*.php')
    ->name('sms-bombing')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'header_comment' => ['header' => $header],
        'array_syntax' => ['syntax' => 'short'],
        'no_useless_else' => true,
        'not_operator_with_successor_space' => true,
        'phpdoc_scalar' => true,
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'single_trait_insert_per_statement' => true,
        'trim_array_spaces' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'new_with_braces' => true,
        'no_space_around_double_colon' => true,
        'object_operator_without_whitespace' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'align_multiline_comment' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_useless_return' => true,
        'return_assignment' => true,
        'blank_line_after_namespace' => true,
        'no_leading_namespace_whitespace' => true,
//        'single_blank_line_before_namespace' => true,
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true
        ],
        'group_import' => true,
        'single_import_per_statement' => false,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'length',
            'imports_order' => ['const', 'class', 'function']
        ],
        'single_line_after_imports' => true,
        'blank_line_after_opening_tag' => true,
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_leading_import_slash' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
            ],
        ],
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'visibility_required' => [
            'elements' => [
                'const',
                'method',
                'property',
            ],
        ],
    ])
    ->setFinder($finder);
