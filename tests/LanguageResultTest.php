<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Language;
use PHPUnit\Framework\TestCase;

/**
 * Class LanguageTest
 *
 * @copyright 2016-2018 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class LanguageResultTest extends TestCase
{
    public function testLimit()
    {
        $l = new Language();

        $count = (new \GlobIterator(__DIR__ . '/../resources/*/*.json'))->count();

        $sample = $l->detect('Example');

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($i, count($sample->limit(0, $i)->close()));
        }
    }

    /**
     * @param string $expected
     * @param string $sample
     * @dataProvider sampleProvider
     */
    public function testWhitelist(string $expected, string $sample)
    {
        $l = new Language();

        $this->assertArrayHasKey($expected, $l->detect($sample)->whitelist($expected)->close());
    }

    /**
     * @param string $expected
     * @param string $sample
     * @dataProvider sampleProvider
     */
    public function testBlacklist(string $expected, string $sample)
    {
        $l = new Language();

        $this->assertArrayNotHasKey($expected, $l->detect($sample)->blacklist($expected)->close());
    }

    /**
     * @param string $expected
     * @param string $sample
     * @dataProvider sampleProvider
     */
    public function testToString(string $expected, string $sample)
    {
        $l = new Language();

        $this->assertEquals($expected, strval($l->detect($sample)));
    }

    public function testJsonSerialize()
    {
        $l = new Language();

        $expected = $l->detect('Example');

        $serialized = json_encode($expected);

        $this->assertEquals($expected->close(), json_decode($serialized, true));
    }

    public function testArrayIterator()
    {
        $l = new Language();

        $actual = $expected = $l->detect('Example');
        $actual = $actual->close();

        foreach ($expected as $key => $value)
        {
            $this->assertEquals($value, $actual[$key]);
        }
    }

    public function testBestResults()
    {
        $l = new Language;

        $a = $l->detect('Example')->bestResults()->close();

        $a = array_values($a);
        $first = $a[0];
        $last = array_slice($a, -1)[0];

        $this->assertLessThanOrEqual(0.025, ($first - $last));
    }

    public function testOffset()
    {
        $l = new Language;

        $result = $l->detect('Example');

        $this->assertTrue(empty($result['NaN']));

        $result[] = null;
        $result['NaN'] = 0;

        $this->assertEquals(0, $result['NaN']);

        unset($result['NaN']);
    }

    /**
     * @return array
     */
    public function sampleProvider()
    {
        return [
            ['de', 'Das ist ein Test.'],
            ['ja', '最近どうですか。'],
            ['hu', 'Nem beszélek magyarul?'],
            ['es', '¡Buenos días! ¡Hasta la vista!'],
            ['hi', 'मुझे हिंदी नहीं आती'],
            ['et', 'Tere tulemast tagasi! Nägemist!'],
            ['pl', 'Czy mówi pan po polsku?'],
            ['fr', 'Où sont les toilettes?'],
        ];
    }
}
