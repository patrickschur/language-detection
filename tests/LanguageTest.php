<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Language;

/**
 * Class LanguageTest
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
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