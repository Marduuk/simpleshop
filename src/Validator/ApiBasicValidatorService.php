<?php
declare(strict_types=1);

namespace App\Validator;

use App\Validator\Constraints\ContainsAlphanumeric;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

/**
 * Class ApiValidatorService
 * @package App\Validator
 */
class ApiBasicValidatorService
{
    /**
     * Validates set of ['field' => 'value'] against not blank and contains alphanumeric constraints
     * @param $fields
     * @return array
     */
    public function validate($fields): array
    {
        $validator = Validation::createValidator();

        $violations = [];
        foreach ($fields as $fieldName => $field) {
            $violation = $validator->validate($field, new NotBlank());

            if ($violation->count() > 0)
                $violations [$fieldName][]= $violation;
        }

        if (!empty($violations))
            return $violations;

        foreach ($fields as $fieldName => $field) {
            $violation = $validator->validate($field, new ContainsAlphanumeric());

            if ($violation->count() > 0)
                $violations [$fieldName][] = $violation;
        }

        return $violations;
    }
}