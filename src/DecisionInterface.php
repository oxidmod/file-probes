<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

interface DecisionInterface
{
    public const READY_ALL = 0;
    public const READY_HALF = 1;
    public const READY_AT_LEAST_ONE = 2;

    public function isReady(int $current): bool;
}
