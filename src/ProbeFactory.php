<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes;

use Oxidmod\FileProbes\Decision\All;
use Oxidmod\FileProbes\Decision\AtLeastHalf;
use Oxidmod\FileProbes\Decision\AtLeastOne;
use Oxidmod\FileProbes\Probe\SimpleProbe;
use Oxidmod\FileProbes\Probe\SwooleProbe;

class ProbeFactory
{
    public function simpleProbe(
        string $filePath,
        int $requiredMarksNum,
        int $startupDecisionType = DecisionInterface::READY_ALL,
        int $runtimeDecisionType = DecisionInterface::READY_HALF
    ): ProbeInterface {
        return $this->create(
            SimpleProbe::class,
            $filePath,
            $this->decision($requiredMarksNum, $startupDecisionType),
            $this->decision($requiredMarksNum, $runtimeDecisionType)
        );
    }

    public function swooleProbe(
        string $filePath,
        int $requiredMarksNum,
        int $startupDecisionType = DecisionInterface::READY_ALL,
        int $runtimeDecisionType = DecisionInterface::READY_HALF
    ): ProbeInterface {
        return $this->create(
            SwooleProbe::class,
            $filePath,
            $this->decision($requiredMarksNum, $startupDecisionType),
            $this->decision($requiredMarksNum, $runtimeDecisionType)
        );
    }

    private function decision(int $requiredMarksNum, int $type): DecisionInterface
    {
        switch ($type) {
            case DecisionInterface::READY_ALL:
                return new All($requiredMarksNum);
            case DecisionInterface::READY_HALF:
                return new AtLeastHalf($requiredMarksNum);
            case DecisionInterface::READY_AT_LEAST_ONE:
                return new AtLeastOne($requiredMarksNum);
            default:
                throw new \InvalidArgumentException(
                    sprintf('Decision type "%d" is unexpected.', $type)
                );
        }
    }

    private function create(
        string $class,
        string $filePath,
        DecisionInterface $startupDecision,
        DecisionInterface $runtimeDecision
    ): ProbeInterface {
        return new $class(
            $filePath,
            $startupDecision,
            $runtimeDecision
        );
    }
}
