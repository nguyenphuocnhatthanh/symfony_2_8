<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\ChoiceValidator;
use Symfony\Component\Validator\ConstraintValidator;

class ChoiceAllowNullValidator extends ChoiceValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Choice) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Choice');
        }

        if (!is_array($constraint->choices) && !$constraint->callback) {
            throw new ConstraintDefinitionException('Either "choices" or "callback" must be specified on constraint Choice');
        }

        if (null === $value) {
            return;
        }

        if ($constraint->multiple && !is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }


        if ($constraint->callback) {
            if (!is_callable($choices = array($this->context->getClassName(), $constraint->callback))
                && !is_callable($choices = $constraint->callback)
            ) {
                throw new ConstraintDefinitionException('The Choice constraint expects a valid callback');
            }

            $choices = call_user_func($choices);

        } else {
            $choices = $constraint->choices;
        }

        if ($constraint->multiple) {
            foreach ($value as $_value) {
                if (!in_array($_value, $choices, $constraint->strict)) {
                    if ($this->context instanceof ExecutionContextInterface) {
                        $this->context->buildViolation($constraint->multipleMessage)
                            ->setParameter('{{ value }}', $this->formatValue($_value))
                            ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                            ->setInvalidValue($_value)
                            ->addViolation();
                    } else {
                        $this->buildViolation($constraint->multipleMessage)
                            ->setParameter('{{ value }}', $this->formatValue($_value))
                            ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                            ->setInvalidValue($_value)
                            ->addViolation();
                    }

                    return;
                }
            }

            $count = count($value);

            if ($constraint->min !== null && $count < $constraint->min) {
                if ($this->context instanceof ExecutionContextInterface) {
                    $this->context->buildViolation($constraint->minMessage)
                        ->setParameter('{{ limit }}', $constraint->min)
                        ->setPlural((int) $constraint->min)
                        ->setCode(Choice::TOO_FEW_ERROR)
                        ->addViolation();
                } else {
                    $this->buildViolation($constraint->minMessage)
                        ->setParameter('{{ limit }}', $constraint->min)
                        ->setPlural((int) $constraint->min)
                        ->setCode(Choice::TOO_FEW_ERROR)
                        ->addViolation();
                }

                return;
            }

            if ($constraint->max !== null && $count > $constraint->max) {
                if ($this->context instanceof ExecutionContextInterface) {
                    $this->context->buildViolation($constraint->maxMessage)
                        ->setParameter('{{ limit }}', $constraint->max)
                        ->setPlural((int) $constraint->max)
                        ->setCode(Choice::TOO_MANY_ERROR)
                        ->addViolation();
                } else {
                    $this->buildViolation($constraint->maxMessage)
                        ->setParameter('{{ limit }}', $constraint->max)
                        ->setPlural((int) $constraint->max)
                        ->setCode(Choice::TOO_MANY_ERROR)
                        ->addViolation();
                }

                return;
            }
        } elseif (!empty($value) && !in_array($value, $choices, $constraint->strict)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                    ->addViolation();
            } else {
                $this->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                    ->addViolation();
            }
        }
    }
}