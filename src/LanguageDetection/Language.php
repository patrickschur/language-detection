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
        $isEmpty = empty($lang);

        /** @var \GlobIterator $json */
        foreach (new \GlobIterator(__DIR__ . '/../../resources/*/*.json') as $json)
        {
            if ($isEmpty || in_array($json->getBasename('.json'), $lang))
            {
                $this->tokens += json_decode(file_get_contents($json->getPathname()), true);
            }
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