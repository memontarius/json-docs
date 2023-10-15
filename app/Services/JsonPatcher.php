<?php

namespace App\Services;

use Clue\JsonMergePatch\Patcher;


class JsonPatcher
{
    public function apply(mixed &$original, mixed $modified): bool
    {
        try {
            $patcher = new Patcher();
            $patched = $patcher->patch($original, $modified);
            $original = $patched;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function create(): static
    {
        return new static;
    }
}
