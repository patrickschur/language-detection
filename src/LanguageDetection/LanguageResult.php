<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class LanguageResult
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package LanguageDetection
 */
class LanguageResult implements \JsonSerializable
{
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
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function __toString()
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
    public function all(): array
    {
        return $this->result;
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