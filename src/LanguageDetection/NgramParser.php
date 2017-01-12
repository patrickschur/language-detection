<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class NgramParser
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
abstract class NgramParser
{
    /**
     * @var int
     */
    protected $minLength = 1;

    /**
     * @var int
     */
    protected $maxLength = 3;

    /**
     * @var int
     */
    protected $maxNgrams = 300;

    /**
     * @param int $minLength
     * @throws \LengthException
     */
    public function setMinLength(int $minLength)
    {
        if ($minLength <= 0 || $minLength >= $this->maxLength)
        {
            throw new \LengthException('$minLength must be greater than zero and less than $this->maxLength.');
        }

        $this->minLength = $minLength;
    }

    /**
     * @param int $maxLength
     * @throws \LengthException
     */
    public function setMaxLength(int $maxLength)
    {
        if ($maxLength <= $this->minLength)
        {
            throw new \LengthException('$maxLength must be greater than $this->minLength.');
        }

        $this->maxLength = $maxLength;
    }

    /**
     * @param int $maxNgrams
     * @throws \LengthException
     */
    public function setMaxNgrams(int $maxNgrams)
    {
        if ($maxNgrams <= 0)
        {
            throw new \LengthException('$maxNgrams must be greater than zero.');
        }

        $this->maxNgrams = $maxNgrams;
    }

    /**
     * @param string $str
     * @return array
     */
    private function tokenize(string $str)
    {
        return array_map(function ($word) {
            return '_' . $word . '_';
        },
            preg_split('/[^\pL]+(?<![\x27\x60\x{2019}])/u', $str, -1, PREG_SPLIT_NO_EMPTY)
        );
    }

    /**
     * @param string $str
     * @return array
     */
    protected function getNgrams(string $str): array
    {
        $tokens = [];

        foreach ($this->tokenize($str) as $word)
        {
            for ($i = $this->minLength; $i <= $this->maxLength; $i++)
            {
                $j = 0;

                while(isset($word[$j + $i - 1]))
                {
                    $tokens[$i][] = mb_substr($word, $j++, $i);
                }
            }
        }

        foreach ($tokens as &$ngram)
        {
            $ngram = array_count_values($ngram);

            $sum = array_sum($ngram);

            $ngram = array_map(function ($number) use (&$sum) {
                return $number / $sum;
            }, $ngram);
        }

        $tokens = array_merge(...$tokens);

        unset($tokens['_']);

        arsort($tokens);

        return array_slice(array_keys($tokens), 0, $this->maxNgrams);
    }
}