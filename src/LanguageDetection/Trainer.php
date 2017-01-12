<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class Trainer
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class Trainer extends NgramParser
{
    public function learn()
    {
        $tokens = [];

        foreach (new \GlobIterator(__DIR__ . '/../../etc/[^_]*') as $file)
        {
            $content = file_get_contents($file->getPathname());
            $content = mb_strtolower($content);

            echo $file->getBasename(), PHP_EOL;

            $tokens[$file->getBasename()] = $this->getNgrams($content);
        }

        file_put_contents(__DIR__ . '/../../etc/_langs.json', json_encode($tokens, JSON_UNESCAPED_UNICODE));
    }
}