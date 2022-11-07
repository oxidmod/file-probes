<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

use Swoole\Atomic;

class SwooleProbe extends AbstractProbe
{
    /** @var Atomic */
    private $counter;

    public function __construct(int $requiredMarksNum, string $filePath)
    {
        $this->counter = new Atomic(0);
        parent::__construct($requiredMarksNum, $filePath);
    }

    protected function doMarkReady(): void
    {
        $this->counter->add(1);
    }

    protected function doMarkNotReady(): void
    {
        $this->counter->sub(1);
    }

    protected function getReadyMarksNum(): int
    {
        return $this->counter->get();
    }
}
