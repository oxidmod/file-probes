<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

class SimpleProbe extends AbstractProbe
{
    /** @var int */
    private $counter = 0;

    protected function doMarkReady(callable $checkAction): void
    {
        $this->counter++;
        $checkAction();
    }

    protected function doMarkNotReady(callable $checkAction): void
    {
        $this->counter--;
        $checkAction();
    }

    protected function getReadyMarksNum(): int
    {
        return $this->counter;
    }
}
