<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class Language
 *
 * @copyright 2016-2017 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class Language extends NgramParser
{
    /**
     * @var array
     */
    private $tokens = [];

    /**
     * Loads all language files
     *
     * @param array $lang
     */
    public function __construct(array $lang = [])
    {
        $isEmpty = empty($lang);

        foreach (glob(__DIR__ . '/../../resources/*/*.json') as $json)
        {
            if ($isEmpty || in_array(basename($json, '.json'), $lang))
            {
                $this->tokens += json_decode(file_get_contents($json), true);
            }
        }
    }

    /**
     * Detects the language from a given text string
     *
     * @param string $str
     * @return LanguageResult
     */
    public function detect(string $str): LanguageResult
    {
        $str = mb_strtolower($str);

        $samples = $this->getNgrams($str);

        $result = [];

        foreach ($this->tokens as $lang => $value)
        {
            $index = $sum = 0;
            $value = array_flip($value);

            foreach ($samples as $v)
            {
                if (isset($value[$v]))
                {
                    $x = $index++ - $value[$v];
                    $y = $x >> (PHP_INT_SIZE * 8);
                    $sum += ($x + $y) ^ $y;
                    continue;
                }

                $sum += $this->maxNgrams;
                ++$index;
            }

            $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
        }

        arsort($result, SORT_NUMERIC);

        return new LanguageResult($result);
    }
}