<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsCategoryValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsCategory) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ContainsCategory');
        }

        $categories = listCategoryForTextMaster();
        if (!in_array($value, $categories)) {
            $this->context->addViolation($constraint->message, [
                '%string%' => $value
            ]);
        }
    }
}