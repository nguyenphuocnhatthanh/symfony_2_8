<?php
namespace AppBundle\Validator\Constraints;

use Doctrine\DBAL\Schema\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsWordCountDocumentValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     */
    public function validate($value, \Symfony\Component\Validator\Constraint $constraint)
    {
        die(dump($value));
    }
}