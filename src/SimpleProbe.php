<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

class SimpleProbe extends AbstractProbe
{
    /** @var int */
    private $counter = 0;

    protected function doMarkReady(): void
    {
        $this->counter++;
    }

    protected function doMarkNotReady(): void
    {
        $this->counter--;
    }

    protected function getReadyMarksNum(): int
    {
        return $this->counter;
    }
}
