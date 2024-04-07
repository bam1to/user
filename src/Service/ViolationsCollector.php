<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsCollector
{

    /**
     * @return string[]
     */
    public function collectViolations(ConstraintViolationListInterface $violationList): array
    {
        $violationsArr = [];

        for ($i = 0; $i < $violationList->count(); $i++) {
            $violationsArr[] = $violationList->get($i);
        }

        return array_map(
            fn (ConstraintViolationInterface $violation): string =>
            $violation->getMessage(),
            $violationsArr
        );
    }
}
