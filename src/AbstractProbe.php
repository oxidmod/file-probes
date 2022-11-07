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

    public function __construct(int $requiredMarksNum, string $filePath)
    {
        $this->requiredMarksNum = $requiredMarksNum;
        $this->filePath = $filePath;

        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    public function markReady(): void
    {
        $this->doMarkReady();
        $this->processMarksNum();
    }

    public function markNotReady(): void
    {
        $this->doMarkNotReady();
        $this->processMarksNum();
    }

    abstract protected function doMarkReady(): void;

    abstract protected function doMarkNotReady(): void;

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
