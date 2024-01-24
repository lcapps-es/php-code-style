<?php

declare(strict_types=1);


use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\Casing\ClassReferenceNameCasingFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTypesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\NoUselessConcatOperatorFixer;
use PhpCsFixer\Fixer\Operator\NoUselessNullsafeOperatorFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\TernaryToElvisOperatorFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\TypeDeclarationSpacesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->sets([
                      SetList::PSR_12,
                  ]);

    $config->rules([
                       // Imports
                       NoUnusedImportsFixer::class,
                       FullyQualifiedStrictTypesFixer::class,
                       GlobalNamespaceImportFixer::class,
                       NoLeadingImportSlashFixer::class,
                       OrderedImportsFixer::class,

                       // Arrays
                       ArrayIndentationFixer::class,
                       TrimArraySpacesFixer::class,

                       // Blank lines
                       BlankLineAfterStrictTypesFixer::class,
                       NoBlankLinesAfterClassOpeningFixer::class,

                       // Spaces
                       SingleLineEmptyBodyFixer::class,
                       CastSpacesFixer::class,
                       TypeDeclarationSpacesFixer::class,
                       TypesSpacesFixer::class,

                       // Casing
                       ClassReferenceNameCasingFixer::class,
                       LowercaseStaticReferenceFixer::class,
                       MagicMethodCasingFixer::class,
                       NativeFunctionCasingFixer::class,
                       NativeTypeDeclarationCasingFixer::class,

                       // Architecture
                       ProtectedToPrivateFixer::class,
                       VisibilityRequiredFixer::class,
                       DateTimeImmutableFixer::class,
                       NoUselessElseFixer::class,

                       // Operator
                       AssignNullCoalescingToCoalesceEqualFixer::class,
                       NoUselessConcatOperatorFixer::class,
                       NoUselessNullsafeOperatorFixer::class,
                       ObjectOperatorWithoutWhitespaceFixer::class,
                       TernaryToElvisOperatorFixer::class,
                       TernaryToNullCoalescingFixer::class,

                       // Testing
                       PhpUnitConstructFixer::class,
                       PhpUnitDedicateAssertFixer::class,
                       PhpUnitDedicateAssertInternalTypeFixer::class,
                       PhpUnitExpectationFixer::class,

                       // Other
                       LineLengthFixer::class,
                       NoNullPropertyInitializationFixer::class,
                       NoUnneededFinalMethodFixer::class,
                       SelfAccessorFixer::class,
                       SelfStaticAccessorFixer::class,
                       NoUnneededControlParenthesesFixer::class,
                       SimplifiedIfReturnFixer::class,
                       TrailingCommaInMultilineFixer::class,
                       DeclareStrictTypesFixer::class,
                       StrictComparisonFixer::class,
                       SingleQuoteFixer::class,
                       StatementIndentationFixer::class,
                   ]);

    $config->ruleWithConfiguration(ArraySyntaxFixer::class, ['syntax' => 'short']);
    $config->ruleWithConfiguration(LineLengthFixer::class, [LineLengthFixer::LINE_LENGTH => 120]);
    $config->ruleWithConfiguration(
        BinaryOperatorSpacesFixer::class,
        [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal'
            ]
        ]
    );
    $config->ruleWithConfiguration(
        GlobalNamespaceImportFixer::class,
        ['import_classes' => true, 'import_constants' => false, 'import_functions' => false]
    );

    $config->ruleWithConfiguration(
        YodaStyleFixer::class,
        ['equal' => false, 'identical' => false, 'less_and_greater' => false]
    );

    $config->ruleWithConfiguration(PhpUnitMethodCasingFixer::class, ['case' => PhpUnitMethodCasingFixer::SNAKE_CASE]);
    $config->ruleWithConfiguration(OrderedTypesFixer::class, ['null_adjustment' => 'always_last']);

    $config->indentation(Option::INDENTATION_SPACES);
};