<?php

declare(strict_types=1);

namespace LCApps\CodeStyle\Fixer;

use Override;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\ConfigurableFixerTrait;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

final class NamedArgumentsFixer implements ConfigurableFixerInterface
{
    use ConfigurableFixerTrait;

    public const ALIGN_SINGLE_SPACE_MINIMAL = 'align_single_space_minimal';
    public const SINGLE_SPACE = 'single_space';
    public const NO_SPACE = 'no_space';

    #[Override]
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound(['(', CT::T_NAMED_ARGUMENT_COLON]);
    }

    #[Override]
    public function isRisky(): bool
    {
        return false;
    }

    #[Override]
    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $currentIndex = 0;

        do {
            $from = $tokens->getNextTokenOfKind($currentIndex, ['(']);
            if ($from === null) {
                break;
            }

            $to = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $from);

            if ($this->isMultilineNamedArguments($tokens, $from, $to)) {
                $this->processNamedArguments($tokens, $from, $to);
            }

            $currentIndex = $to + 1;
        } while (true);
    }

    #[Override]
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Configures spacing before colons in named arguments',
            [
                new CodeSample(
                    <<<'PHP'
<?php
$result = callFunction(
    first: 1,
    second: 2,
    longerName: 3,
);
PHP
                ),
                new CodeSample(
                    <<<'PHP'
<?php
$result = callFunction(
    first: 1,
    second: 2,
    longerName: 3,
);
PHP
                    ,
                    ['spacing' => self::ALIGN_SINGLE_SPACE_MINIMAL]
                ),
            ]
        );
    }

    #[Override]
    public function getName(): string
    {
        return self::class;
    }

    #[Override]
    public function getPriority(): int
    {
        return -30;
    }

    #[Override]
    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    #[Override]
    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder('spacing', 'Spacing style before the colon'))
                ->setAllowedValues([self::ALIGN_SINGLE_SPACE_MINIMAL, self::SINGLE_SPACE, self::NO_SPACE])
                ->setDefault(self::ALIGN_SINGLE_SPACE_MINIMAL)
                ->getOption(),
        ]);
    }

    private function isMultilineNamedArguments(Tokens $tokens, int $from, int $to): bool
    {
        $hasNamedArgument = false;
        $hasNewline       = false;

        for ($index = $from; $index <= $to; ++$index) {
            if ($tokens[$index]->isGivenKind(CT::T_NAMED_ARGUMENT_COLON)) {
                $hasNamedArgument = true;
            }

            if ($tokens[$index]->isGivenKind(T_WHITESPACE) && strpos($tokens[$index]->getContent(), "\n") !== false) {
                $hasNewline = true;
            }
        }

        return $hasNamedArgument && $hasNewline;
    }

    private function processNamedArguments(Tokens $tokens, int $from, int $to): void
    {
        $spacing = $this->configuration['spacing'] ?? self::ALIGN_SINGLE_SPACE_MINIMAL;

        switch ($spacing) {
            case self::ALIGN_SINGLE_SPACE_MINIMAL:
                $this->alignNamedArguments($tokens, $from, $to);
                break;
            case self::SINGLE_SPACE:
                $this->setSingleSpace($tokens, $from, $to);
                break;
            case self::NO_SPACE:
                $this->removeSpaces($tokens, $from, $to);
                break;
        }
    }

    private function alignNamedArguments(Tokens $tokens, int $from, int $to): void
    {
        $colonData = $this->collectColonData($tokens, $from, $to);

        if (count($colonData) < 2) {
            return;
        }

        $maxNameLength = 0;
        foreach ($colonData as $data) {
            if ($data['nameLength'] > $maxNameLength) {
                $maxNameLength = $data['nameLength'];
            }
        }

        foreach (array_reverse($colonData) as $data) {
            $spacesNeeded   = $maxNameLength - $data['nameLength'];
            $colonIndex     = $data['colonIndex'];
            $prevTokenIndex = $colonIndex - 1;

            if ($tokens[$prevTokenIndex]->isGivenKind(T_WHITESPACE)) {
                $currentSpaces = strlen($tokens[$prevTokenIndex]->getContent());
                $newSpaces     = $spacesNeeded;

                if ($currentSpaces !== $newSpaces) {
                    if ($newSpaces === 0) {
                        $tokens->clearAt($prevTokenIndex);
                    } else {
                        $tokens[$prevTokenIndex] = new Token([T_WHITESPACE, str_repeat(' ', $newSpaces)]);
                    }
                }
            } elseif ($spacesNeeded > 0) {
                $tokens->insertAt($colonIndex, new Token([T_WHITESPACE, str_repeat(' ', $spacesNeeded)]));
            }
        }
    }

    private function setSingleSpace(Tokens $tokens, int $from, int $to): void
    {
        $colonData = $this->collectColonData($tokens, $from, $to);

        foreach (array_reverse($colonData) as $data) {
            $colonIndex     = $data['colonIndex'];
            $prevTokenIndex = $colonIndex - 1;

            if ($tokens[$prevTokenIndex]->isGivenKind(T_WHITESPACE)) {
                if ($tokens[$prevTokenIndex]->getContent() !== ' ') {
                    $tokens[$prevTokenIndex] = new Token([T_WHITESPACE, ' ']);
                }
            } else {
                $tokens->insertAt($colonIndex, new Token([T_WHITESPACE, ' ']));
            }
        }
    }

    private function removeSpaces(Tokens $tokens, int $from, int $to): void
    {
        $colonData = $this->collectColonData($tokens, $from, $to);

        foreach (array_reverse($colonData) as $data) {
            $colonIndex     = $data['colonIndex'];
            $prevTokenIndex = $colonIndex - 1;

            if ($tokens[$prevTokenIndex]->isGivenKind(T_WHITESPACE)) {
                $tokens->clearAt($prevTokenIndex);
            }
        }
    }

    private function collectColonData(Tokens $tokens, int $from, int $to): array
    {
        $colonData  = [];
        $depthLevel = 0;

        for ($index = $from; $index <= $to; ++$index) {
            $token = $tokens[$index];

            if ($token->equals('(') || $token->equals('[') || $token->equals('{')) {
                ++$depthLevel;
                continue;
            }

            if ($token->equals(')') || $token->equals(']') || $token->equals('}')) {
                --$depthLevel;
                continue;
            }

            if ($depthLevel !== 1) {
                continue;
            }

            if (!$token->isGivenKind(CT::T_NAMED_ARGUMENT_COLON)) {
                continue;
            }

            $nameIndex = $tokens->getPrevMeaningfulToken($index);
            if ($nameIndex === null) {
                continue;
            }

            $nameToken = $tokens[$nameIndex];
            if (!$nameToken->isGivenKind(CT::T_NAMED_ARGUMENT_NAME)) {
                continue;
            }

            $colonData[] = [
                'colonIndex' => $index,
                'nameLength' => strlen($nameToken->getContent()),
            ];
        }

        return $colonData;
    }
}
