<?php

namespace App\Services\JsonPatcher;

interface JsonPatcherInterface
{
    public function apply(mixed &$origin, mixed $patch): bool;
}
