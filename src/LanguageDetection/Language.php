<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class Language
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class Language extends NgramParser
{
    /**
     * @var array
     */
    private $tokens = [];

    public function __construct(array $lang = [])
    {
        $filename = __DIR__ . '/../../etc/_langs.json';

        if (file_exists($filename))
        {
            $this->tokens = json_decode(file_get_contents($filename), true);
        }

        if (!empty($lang))
        {
            $this->tokens = array_intersect_key($this->tokens, array_flip($lang));
        }
    }

    /**
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

            foreach ($samples as $k => $v)
            {
                if (!in_array($v, $value))
                {
                    $sum += $this->maxNgrams;
                    $index++;
                    continue;
                }

                $sum += abs($index - array_search($v, $value));
                $index++;
            }

            $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
        }

        arsort($result);

        return new LanguageResult($result);
    }
}