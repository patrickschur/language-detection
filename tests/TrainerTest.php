<?php

declare(strict_types = 1);

namespace LanguageDetection\Tests;

use LanguageDetection\Trainer;
use PHPUnit\Framework\TestCase;

/**
 * Class TrainerTest
 *
 * @copyright Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection\Tests
 */
class TrainerTest extends TestCase
{
    public function testLearn()
    {
        $t = new Trainer();
        $t->learn();

        $this->expectOutputString('');
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
        foreach (new \GlobIterator(__DIR__ . '/../resources/*/*.txt') as $json)
        {
            $this->assertIsWritable($json->getPathname());
        }
    }

    public function testExceptionIsMinLengthGreaterThanMaxLength()
    {
        $this->expectException(\LogicException::class);

        $t = new Trainer();

        $t->setMaxLength(3);
        $t->setMinLength(42);
    }

    public function testExceptionIsMinLengthLessThanZero()
    {
        $this->expectException(\LogicException::class);

        $t = new Trainer();

        $t->setMinLength(-42);
    }

    public function testExceptionIsMaxLengthLessThanMinLength()
    {
        $this->expectException(\LogicException::class);

        $t = new Trainer();

        $t->setMinLength(1);
        $t->setMaxLength(0);
    }

    public function testExceptionIsMaxNgramsEqualToZero()
    {
        $this->expectException(\LogicException::class);

        $t = new Trainer();

        $t->setMaxNGrams(0);
    }

    public function testExceptionIsMaxNgramsLessThanZero()
    {
        $this->expectException(\LogicException::class);

        $t = new Trainer();

        $t->setMaxNGrams(-2);
    }
}
