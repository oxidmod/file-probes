<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes\Decision;

class AtLeastHalf extends AbstractDecision
{
    public function isReady(int $current): bool
    {
        return $current >= (int)(ceil($this->requiredMarksNum / 2));
    }
}
