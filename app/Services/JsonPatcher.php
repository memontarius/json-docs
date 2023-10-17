<?php

namespace App\Services;

use Clue\JsonMergePatch\Patcher;

class JsonPatcher
{
    /**
     * Apply patching to json
     *
     * @param mixed $original
     * @param mixed $modified
     * @return bool
     */
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
}
