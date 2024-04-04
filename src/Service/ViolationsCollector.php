<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsCollector
{
    public function collectViolations(ConstraintViolationListInterface $violationList): array
    {
        $violationsArr = $violationList->getIterator()->getArrayCopy();

        return array_map(fn (ConstraintViolationInterface $violation): string => $violation->getMessage(), $violationsArr);
    }
}
