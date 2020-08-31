<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class ContainsAlphanumericValidator
 * @package App\Validator\Constraints
 */
class ContainsAlphanumericValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsAlphanumeric)
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);

        if (!is_string($value))
            throw new UnexpectedValueException($value, 'string');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}