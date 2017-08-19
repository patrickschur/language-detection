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
    protected $tokens = [];

    /**
     * Loads all language files
     *
     * @param array $lang List of ISO 639-1 codes, that should be used in the detection phase
     * @param string $dirname Name of the directory where the translations files are located
     */
    public function __construct(array $lang = [], string $dirname = '')
    {
        if (empty($dirname))
        {
            $dirname = __DIR__ . '/../../resources/*/*.json';
        }
        else if (!is_dir($dirname) || !is_readable($dirname))
        {
            throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
        }
        else
        {
            $dirname = rtrim($dirname, '/');
            $dirname .= '/*/*.json';
        }

        $isEmpty = empty($lang);

        foreach (glob($dirname) as $json)
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

        if (count($samples) > 0)
        {
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
        }

        return new LanguageResult($result);
    }
}
