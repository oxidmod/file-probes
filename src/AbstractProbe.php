<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

/**
 * Put $filePath file when all requested workers started
 * Remove $filePath when no workers left
 */
abstract class AbstractProbe implements ProbeInterface
{
    /** @var int */
    protected $requiredMarksNum;

    /** @var string */
    protected $filePath;

    /** @var callable */
    protected $checkAction;

    public function __construct(int $requiredMarksNum, string $filePath)
    {
        $this->requiredMarksNum = $requiredMarksNum;
        $this->filePath = $filePath;
        $this->checkAction = function () {
            $this->processMarksNum();
        };

        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
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

    protected function processMarksNum(): void
    {
        if ($this->getReadyMarksNum() === $this->requiredMarksNum) {
            file_put_contents($this->filePath, '');
        }

        if ($this->getReadyMarksNum() === 0 && file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}
