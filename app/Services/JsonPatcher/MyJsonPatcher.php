<?php

namespace App\Services\JsonPatcher;

class MyJsonPatcher implements JsonPatcherInterface
{
    public function apply(mixed &$origin, mixed $patch): bool
    {
        $this->patch($origin, $patch);
        return true;
    }

    private function patch(mixed &$origin, mixed $patch): mixed
    {
        if (!is_object($patch)) {
            return $patch;
        }
        if (!is_object($origin)) {
            $origin = (object)[];
        }

        foreach ($patch as $key => $value) {
            if (is_null($value)) {
                unset($origin->$key);
            } else {
                $origin->$key = $this->patch($origin->$key, $value);
            }
        }

        return $origin;
    }
}
