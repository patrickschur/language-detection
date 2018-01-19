<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Language;
use LanguageDetection\Tokenizer\TokenizerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class LanguageTest
 *
 * @copyright 2016-2018 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class LanguageTest extends TestCase
{
    public function testAll()
    {
        $l = new Language();

        foreach (new \GlobIterator(__DIR__ . '/../resources/*/*.txt') as $txt)
        {
            $content = file_get_contents($txt->getPathname());

            $this->assertEquals(key($l->detect($content)->close()), $txt->getBasename('.txt'));
        }
    }

    public function testConstructor()
    {
        $l = new Language(['de', 'en', 'nl']);

        $array = $l->detect('Das ist ein Test')->close();

        $this->assertEquals(3, count($array));

        $this->assertArrayHasKey('de', $array);
        $this->assertArrayHasKey('en', $array);
        $this->assertArrayHasKey('nl', $array);
    }

    public function testTokenizer()
    {
        $stub = $this->createMock(Language::class);

        $stub->method('setTokenizer')->willReturn('');

        /** @var Language $stub */
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertEquals('', $stub->setTokenizer(new class implements TokenizerInterface
        {
            public function tokenize(string $str): array
            {
                return preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
            }
        }));
    }

    /**
     * @param $expected
     * @param $sample
     * @dataProvider sampleProvider
     */
    public function testSamples(string $expected, string $sample)
    {
        $l = new Language();

        $this->assertEquals($expected, key($l->detect($sample)->close()));
    }

    /**
     * @return array
     */
    public function sampleProvider()
    {
        return [
            ['de', 'Ich wünsche dir noch einen schönen Tag'],
            ['ja', '最近どうですか。'],
            ['en', 'This sentences should be too small to be recognized.'],
            ['nl', 'Mag het een onsje meer zijn? '],
            ['hi', 'मुझे हिंदी नहीं आती'],
            ['et', 'Tere tulemast tagasi! Nägemist!'],
            ['pl', 'Wszystkiego najlepszego z okazji urodzin!'],
            ['pl', 'Czy mówi pan po polsku?'],
            ['fr', 'Où sont les toilettes?']
        ];
    }
}