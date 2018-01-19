<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Trainer;
use PHPUnit\Framework\TestCase;

/**
 * Class TrainerTest
 *
 * @copyright 2016-2018 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class TrainerTest extends TestCase
{
    public function testLearn()
    {
        $t = new Trainer();

        $t->setMaxNgrams(350);

        $expected = '';

        foreach (new \GlobIterator(__DIR__ . '/../resources/*/*.json') as $json)
        {
            $expected .= $json->getBasename('.json') . PHP_EOL;
        }

        $this->expectOutputString($expected);

        $t->learn();
    }

    public function testFilesAreReadable()
    {
        foreach (new \GlobIterator(__DIR__ . '/../resources/*/*') as $file)
        {
            $this->assertIsReadable($file->getPathname());
        }
    }

    public function testFileIsWriteable()
    {
        foreach (new \GlobIterator(__DIR__ . '/../resources/*/*.json') as $json)
        {
            $this->assertIsWritable($json->getPathname());
        }
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionIsMinLengthGreaterThanMaxLength()
    {
        $t = new Trainer();

        $t->setMaxLength(3);
        $t->setMinLength(42);
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionIsMinLengthLessThanZero()
    {
        $t = new Trainer();

        $t->setMinLength(-42);
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionIsMaxLengthLessThanMinLength()
    {
        $t = new Trainer();

        $t->setMinLength(1);
        $t->setMaxLength(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionIsMaxNgramsEqualToZero()
    {
        $t = new Trainer();

        $t->setMaxNGrams(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testExceptionIsMaxNgramsLessThanZero()
    {
        $t = new Trainer();

        $t->setMaxNGrams(-2);
    }
}