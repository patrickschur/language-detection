<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class Trainer
 *
 * @copyright 2016-2017 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class Trainer extends NgramParser
{
    /**
     * Generates language profiles for all language files
     *
     * @param string $dirname Name of the directory where the translations files are located
     * @return void
     */
    public function learn(string $dirname = '')
    {
        if (empty($dirname))
        {
            $dirname = __DIR__ . '/../../resources/*/*.txt';
        }
        else if (!is_dir($dirname) || !is_readable($dirname))
        {
            throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
        }
        else
        {
            $dirname = rtrim($dirname, '/');
            $dirname .= '/*/*.txt';
        }

        /** @var \GlobIterator $txt */
        foreach (new \GlobIterator($dirname) as $txt)
        {
            $content = mb_strtolower(file_get_contents($txt->getPathname()));

            echo $txt->getBasename('.txt'), PHP_EOL;

            file_put_contents(
                substr_replace($txt->getPathname(), 'json', -3),
                json_encode(
                    [ $txt->getBasename('.txt') => $this->getNgrams($content) ],
                    JSON_UNESCAPED_UNICODE
                )
            );
        }
    }
}