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
        if (!is_callable($language_to = array($this->context->getClassName(), $constraint->callback))
            && !is_callable($language_to = $constraint->callback)
        ) {
            throw new ConstraintDefinitionException('The Choice constraint expects a valid callback');
        } else {

            $language_to = call_user_func($language_to);
            die(dump($language_to));
        }



        if (!in_array($value, $langs) || $value == $language_to) {
            $this->context->addViolation($constraint->message, [
                '%string%' => $value
            ]);
        }
    }
}