<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes\Decision;

class All extends AbstractDecision
{
    public function isReady(int $current): bool
    {
        return $current === $this->requiredMarksNum;
    }
}
