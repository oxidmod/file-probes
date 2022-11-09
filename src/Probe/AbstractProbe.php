<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes\Probe;

use Oxidmod\FileProbes\DecisionInterface;
use Oxidmod\FileProbes\ProbeInterface;

/**
 * Put $filePath file when all requested workers started
 * Remove $filePath when no workers left
 */
abstract class AbstractProbe implements ProbeInterface
{
    /** @var string */
    protected $filePath;

    /** @var callable */
    protected $checkAction;

    /** @var DecisionInterface */
    protected $startupDecision;

    /** @var DecisionInterface */
    protected $runtimeDecision;

    /** @var bool */
    protected $isStartupCompleted = false;

    public function __construct(
        string $filePath,
        DecisionInterface $startupDecision,
        DecisionInterface $runtimeDecision
    ) {
        $this->filePath = $filePath;
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }


        $this->startupDecision = $startupDecision;
        $this->runtimeDecision = $runtimeDecision;

        $this->checkAction = function () {
            if (!$this->isStartupCompleted) {
                $this->isStartupCompleted = $this->processMarksNum($this->startupDecision);
            }

            if ($this->isStartupCompleted) {
                $this->processMarksNum($this->runtimeDecision);
            }
        };
    }

    public function markReady(): void
    {
        $this->doMarkReady($this->checkAction);
    }

    public function markNotReady(): void
    {
        $this->doMarkNotReady($this->checkAction);
    }

    abstract protected function doMarkReady(callable $checkAction): void;

    abstract protected function doMarkNotReady(callable $checkAction): void;

    abstract protected function getReadyMarksNum(): int;

    private function processMarksNum(DecisionInterface $decision): bool
    {
        $isReady = $decision->isReady($this->getReadyMarksNum());
        if ($isReady) {
            file_put_contents($this->filePath, '');
        } elseif (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        return $isReady;
    }
}
