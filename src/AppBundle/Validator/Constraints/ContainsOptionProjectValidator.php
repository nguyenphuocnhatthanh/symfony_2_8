<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsOptionProjectValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsOptionProject) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ContainsOptionProject');
        }

        $message = 'The Options is not valid. ';
        $valid = empty($value) ? 0 : 1;

        $options = ['language_level', 'quality', 'expertise', 'specific_attachment', 'priority', 'uniq_author'];
        $array_keys_request = array_keys($value);
        $options_diff = array_diff($array_keys_request, $options);

        if (count($options_diff) != 0) {
            foreach ($options_diff as $fields)
            $message .= 'Option '.$fields.' is not possible. ';
            $this->context->addViolation($message);

            return;
        }

        if (isset($value['language_level']) && !in_array($value['language_level'], ['regular', 'premium', 'enterprise'])) {
            $message .= 'The Language level is not valid. ';
            $valid = 0;
        }

        if (isset($value['quality']) && !is_bool($value['quality'])) {
            $message .= 'The Quality is not valid. ';
            $valid = 0;
        }

        if (isset($value['specific_attachment']) && !is_bool($value['specific_attachment'])) {
            $message .= 'The Specific attachment is not valid. ';
            $valid = 0;
        }

        if (isset($value['priority']) && !is_bool($value['priority'])) {
            $message .= 'The Priority is not valid. ';
            $valid = 0;
        }

        if (isset($value['uniq_author']) && !is_bool($value['uniq_author'])) {
            $message .= 'The Uniq author is not valid. ';
            $valid = 0;
        }

        if (0 === $valid) {
            $this->context->addViolation($message);

        }
    }
}