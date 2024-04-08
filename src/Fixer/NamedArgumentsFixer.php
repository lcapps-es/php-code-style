<?php

declare(strict_types=1);

namespace LCApps\CodeStyle\Fixer;

use Override;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

final class NamedArgumentsFixer implements FixerInterface
{
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
            if ($from) {
                $to          = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $from);
                $totalSpaces = 0;
                if ($this->checkIfNamedArguments($tokens, $from, $to)) {
                    $totalSpaces = $this->alignNamedArguments($tokens, $from, $to);
                }

                $currentIndex = $to + 1 + $totalSpaces;
            }
        } while ($from);
    }

    #[Override]
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'This fixer will align named arguments',
            [
                new CodeSample(
                    '
callFunction(
    first: 1,
    second: 2
);'
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
        var_dump($file->getFilename());
        return $file->getFilename() === 'CreateBrandCommandHandler.php';
    }

    private function checkIfNamedArguments(Tokens $tokens, int $from, int $to): bool
    {
        $content = '';
        for ($index = $from; $index <= $to; ++$index) {
            $content .= $tokens[$index]->getContent();
        }

        preg_match_all('/^\((\n* *[a-zA-Z]+: *[a-zA-Z0-9"\':()$\-> ]+,*)+\n* *\)$/', $content, $output);

        return sizeof($output[0]) > 0;
    }

    private function alignNamedArguments(Tokens $tokens, int $from, int $to): int
    {
        $content = '';
        for ($index = $from; $index <= $to; ++$index) {
            $content .= $tokens[$index]->getContent();
        }

        $explodedContent = explode("\n", $content);
        $positions       = [];
        $maxPosition     = 0;
        foreach ($explodedContent as $contentLine) {
            $position = strpos($contentLine, ':');
            if ($position !== false) {
                $positions[] = $position;

                if ($position > $maxPosition) {
                    $maxPosition = $position;
                }
            }
        }

        $spaces = [];
        foreach ($positions as $position) {
            $spaces[] = $maxPosition - $position;
        }

        $colonIndexes = [];
        for ($index = $from + 1; $index < $to; ++$index) {
            if ($tokens[$index]->isGivenKind(CT::T_NAMED_ARGUMENT_COLON)) {
                $colonIndexes[] = $index;
            }
        }

        $totalSpaces     = 0;
        $revSpaces       = array_reverse($spaces);
        $revColonIndexes = array_reverse($colonIndexes);

        if (sizeof($spaces) === sizeof($colonIndexes)) {
            for ($index = 0; $index < sizeof($spaces); ++$index) {
                $totalSpaces += $revSpaces[$index];
                for ($i = 0; $i < $revSpaces[$index]; ++$i) {
                    $tokens->insertAt($revColonIndexes[$index], new Token([\T_WHITESPACE, ' ']));
                }
            }
        }

        return $totalSpaces;
    }
}
