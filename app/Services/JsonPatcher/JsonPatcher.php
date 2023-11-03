<?php

namespace App\Services\JsonPatcher;

use Clue\JsonMergePatch\Patcher;

class JsonPatcher implements JsonPatcherInterface
{
    private readonly Patcher $patcher;

    public function __construct()
    {
        $this->patcher = new Patcher();
    }

    /**
     * Apply patching to json
     *
     * @param mixed $origin
     * @param mixed $patch
     * @return bool
     */
    public function apply(mixed &$origin, mixed $patch): bool
    {
        try {
            $patched = $this->patcher->patch($origin, $patch);
            $origin = $patched;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
