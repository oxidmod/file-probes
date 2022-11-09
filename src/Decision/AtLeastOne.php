<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes\Decision;

class AtLeastOne extends AbstractDecision
{
    public function isReady(int $current): bool
    {
        return $current >= 1;
    }
}
