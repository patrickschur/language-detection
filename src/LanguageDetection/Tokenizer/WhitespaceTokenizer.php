<?php

declare(strict_types = 1);

namespace LanguageDetection\Tokenizer;

/**
 * Class WhitespaceTokenizer
 *
 * @copyright 2016-2017 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class WhitespaceTokenizer implements TokenizerInterface
{
    /**
     * @param string $str
     * @return array
     */
    public function tokenize(string $str): array
    {
        return array_map(function ($word) {
                return "_{$word}_";
            },
            preg_split('/[^\pL]+(?<![\x27\x60\x{2019}])/u', $str, -1, PREG_SPLIT_NO_EMPTY)
        );
    }
}