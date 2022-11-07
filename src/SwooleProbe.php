<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

use Swoole\Atomic;
use Swoole\Lock;

class SwooleProbe extends AbstractProbe
{
    /** @var Atomic */
    private $counter;

    /** @var Lock */
    private $lock;

    public function __construct(int $requiredMarksNum, string $filePath)
    {
        $this->counter = new Atomic(0);
        $this->lock = new Lock();

        parent::__construct($requiredMarksNum, $filePath);
    }

    protected function doMarkReady(callable $checkAction): void
    {
        if ($this->lock->lock()) {
            $this->counter->add(1);

            $checkAction();

            $this->lock->unlock();
        }
    }

    protected function doMarkNotReady(callable $checkAction): void
    {
        if ($this->lock->lock()) {
            $this->counter->sub(1);

            $checkAction();

            $this->lock->unlock();
        }
    }

    protected function getReadyMarksNum(): int
    {
        return $this->counter->get();
    }
}
