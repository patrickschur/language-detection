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
class LanguageResultTest extends \PHPUnit_Framework_TestCase
{
    public function testLimit()
    {
        $l = new Language();

        $count = (new \GlobIterator(__DIR__ . '/../etc/[^_]*'))->count();

        $sample = $l->detect("Example");

        for ($i = 0; $i < $count; $i++)
        {
            $this->assertEquals($i, count($sample->limit(0, $i)->all()));
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

        $this->assertArrayHasKey($expected, $l->detect($sample)->whitelist($expected)->all());
    }

    /**
     * @param string $expected
     * @param string $sample
     * @dataProvider sampleProvider
     */
    public function testBlacklist(string $expected, string $sample)
    {
        $l = new Language();

        $this->assertArrayNotHasKey($expected, $l->detect($sample)->blacklist($expected)->all());
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

        $expected = $l->detect("Example");

        $serialized = json_encode($expected);

        $this->assertEquals($expected->all(), json_decode($serialized, true));
    }

    /**
     * @return array
     */
    public function sampleProvider()
    {
        return [
            ['de', 'Das ist ein Test.'],
            ['jp', '最近どうですか。'],
            ['hu', 'Nem beszélek magyarul?'],
            ['es', '¡Buenos días! ¡Hasta la vista!'],
            ['hi', 'मुझे हिंदी नहीं आती'],
            ['et', 'Tere tulemast tagasi! Nägemist!'],
            ['pl', 'Czy mówi pan po polsku?'],
            ['fr', 'Où sont les toilettes?'],
        ];
    }
}