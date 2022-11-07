<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

interface ProbeInterface
{
    public function markReady(): void;

    public function markNotReady(): void;
}
