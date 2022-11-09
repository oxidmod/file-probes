<?php
declare(strict_types=1);

namespace Oxidmod\FileProbes\Decision;

use Oxidmod\FileProbes\DecisionInterface;

abstract class AbstractDecision implements DecisionInterface
{
    /** @var int */
    protected $requiredMarksNum;

    public function __construct(int $requiredMarksNum)
    {
        $this->requiredMarksNum = $requiredMarksNum;
    }
}
