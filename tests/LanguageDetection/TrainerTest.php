<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Trainer;

/**
 * Class TrainerTest
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class TrainerTest extends \PHPUnit_Framework_TestCase
{
    public function testLearn()
    {
        $t = new Trainer();

        $t->setMaxNgrams(350);

        $expected = '';

        foreach (new \GlobIterator(__DIR__ . '/../etc/[^_]*') as $file)
        {
            $expected .= $file->getBasename() . PHP_EOL;
        }

        $this->expectOutputString($expected);

        $t->learn();
    }

    public function testFilesAreReadable()
    {
        foreach (new \GlobIterator(__DIR__ . '/../etc/[^_]*') as $file)
        {
            $this->assertIsReadable($file->getPathname());
        }
    }

    public function testFileIsWriteable()
    {
        $this->assertIsWritable(__DIR__ . '/../etc/_langs.json');
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