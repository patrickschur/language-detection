<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class LanguageResult
 *
 * @copyright 2016-2017 Patrick Schur
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class LanguageResult implements \JsonSerializable, \IteratorAggregate, \ArrayAccess
{
    const THRESHOLD = .025;

    /**
     * @var array
     */
    private $result = [];

    /**
     * LanguageResult constructor.
     * @param array $result
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->result[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->result[] = $value;
        } else {
            $this->result[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return key($this->result);
    }

    /**
     * @param \string[] ...$whitelist
     * @return LanguageResult
     */
    public function whitelist(string ...$whitelist): LanguageResult
    {
        return new LanguageResult(array_intersect_key($this->result, array_flip($whitelist)));
    }

    /**
     * @param \string[] ...$blacklist
     * @return LanguageResult
     */
    public function blacklist(string ...$blacklist): LanguageResult
    {
        return new LanguageResult(array_diff_key($this->result, array_flip($blacklist)));
    }

    /**
     * @return array
     */
    public function close(): array
    {
        return $this->result;
    }

    /**
     * @return LanguageResult
     */
    public function bestResults(): LanguageResult
    {
        if (!count($this->result))
        {
            return new LanguageResult;
        }

        $first = array_values($this->result)[0];

        return new LanguageResult(array_filter($this->result, function ($value) use ($first) {
            return ($first - $value) <= self::THRESHOLD ? true : false;
        }));
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->result);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return LanguageResult
     */
    public function limit(int $offset, int $length = null): LanguageResult
    {
        return new LanguageResult(array_slice($this->result, $offset, $length));
    }
}
