<?php

declare(strict_types = 1);

namespace LanguageDetection;

/**
 * Class LanguageResult
 *
 * @author Patrick Schur
 * @package LanguageDetection
 */
class LanguageResult
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
     * @param \string[] ...$whitelist
     * @return array
     */
    public function whitelist(string ...$whitelist): array
    {
        return array_intersect_key($this->result, array_flip($whitelist));
    }

    /**
     * @param \string[] ...$blacklist
     * @return array
     */
    public function blacklist(string ...$blacklist): array
    {
        return array_diff_key($this->result, array_flip($blacklist));
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->result;
    }
}