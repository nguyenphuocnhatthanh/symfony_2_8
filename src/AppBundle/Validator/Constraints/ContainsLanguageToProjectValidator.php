<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
class ContainsLanguageToProjectValidator extends ConstraintValidator
{
    protected $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsLanguageToProject) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ContainsLanguageToProject');
        }

        $langs = languagesSupportByTextmaster();

        if (!in_array($value, $langs)) {
            $this->context->addViolation($constraint->message, [
                '%string%' => $value
            ]);
        }
    }
}