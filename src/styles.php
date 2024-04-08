<?php

declare(strict_types=1);


use LCApps\CodeStyle\Fixer\NamedArgumentsFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\Basic\NoMultipleStatementsPerLineFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\Casing\ClassReferenceNameCasingFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedTypesFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
use PhpCsFixer\Fixer\Comment\NoEmptyCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SimplifiedIfReturnFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoSpacesAfterFunctionNameFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnneededImportAliasFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareParenthesesFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAroundConstructFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Operator\NewWithParenthesesFixer;
use PhpCsFixer\Fixer\Operator\NoSpaceAroundDoubleColonFixer;
use PhpCsFixer\Fixer\Operator\NoUselessConcatOperatorFixer;
use PhpCsFixer\Fixer\Operator\NoUselessNullsafeOperatorFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToElvisOperatorFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitExpectationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypeDeclarationFixer;
use PhpCsFixer\Fixer\Whitespace\LineEndingFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
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
                       NoLeadingImportSlashFixer::class,
                       OrderedImportsFixer::class,

                       // Arrays
                       ArrayIndentationFixer::class,
                       TrimArraySpacesFixer::class,

                       // Blank lines
                       BlankLineAfterStrictTypesFixer::class,
                       NoBlankLinesAfterClassOpeningFixer::class,
                       BlankLineAfterNamespaceFixer::class,
                       BlankLineAfterOpeningTagFixer::class,

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
                       ClassAttributesSeparationFixer::class,
                       ClassDefinitionFixer::class,
                       CompactNullableTypeDeclarationFixer::class,
                       ConstantCaseFixer::class,
                       ControlStructureBracesFixer::class,
                       ControlStructureContinuationPositionFixer::class,

                       DeclareParenthesesFixer::class,
                       ElseifFixer::class,
                       FunctionDeclarationFixer::class,
                       IncrementStyleFixer::class,
                       LineEndingFixer::class,
                       LowercaseCastFixer::class,
                       LowercaseKeywordsFixer::class,
                       MagicConstantCasingFixer::class,
                       MethodArgumentSpaceFixer::class,
                       MethodChainingIndentationFixer::class,
                       MultilineWhitespaceBeforeSemicolonsFixer::class,
                       NewWithParenthesesFixer::class,
                       NoEmptyCommentFixer::class,
                       NoEmptyStatementFixer::class,
                       NoMultilineWhitespaceAroundDoubleArrowFixer::class,
                       NoMultipleStatementsPerLineFixer::class,
                       NoShortBoolCastFixer::class,
                       NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
                       NoSpaceAroundDoubleColonFixer::class,
                       NoSpacesAfterFunctionNameFixer::class,
                       NoSpacesAroundOffsetFixer::class,
                       NoUnneededBracesFixer::class,
                       NoUnneededImportAliasFixer::class,
                       NoUselessReturnFixer::class,
                       NoWhitespaceBeforeCommaInArrayFixer::class,
                       NoWhitespaceInBlankLineFixer::class,
                       OperatorLinebreakFixer::class,
                       SingleLineAfterImportsFixer::class,
                       SingleSpaceAroundConstructFixer::class,
                       StandardizeNotEqualsFixer::class,
                       TernaryOperatorSpacesFixer::class,
                       NamedArgumentsFixer::class,
                   ]);

    $config->ruleWithConfiguration(ArraySyntaxFixer::class, ['syntax' => 'short']);
    $config->ruleWithConfiguration(
        LineLengthFixer::class,
        [
            LineLengthFixer::LINE_LENGTH => 120,
            LineLengthFixer::INLINE_SHORT_LINES => false
        ]
    );
    $config->ruleWithConfiguration(ConcatSpaceFixer::class, ['spacing' => 'one']);

    $config->ruleWithConfiguration(
        BinaryOperatorSpacesFixer::class,
        [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal',
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