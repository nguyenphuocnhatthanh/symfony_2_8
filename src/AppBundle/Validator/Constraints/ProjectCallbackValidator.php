<?php
namespace AppBundle\Validator\Constraints;


use AppBundle\Entity\Project;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class ProjectCallbackValidator
 * @package AppBundle\Validator\Constraints
 */
class ProjectCallbackValidator
{
    /**
     * @param Project $object
     * @param ExecutionContextInterface $context
     */
    public static function validate(Project $object, ExecutionContextInterface $context)
    {
        if ($object->getLanguageTo() == $object->getLanguageFrom()) {
            $context->buildViolation('The language is equals language from')
                ->atPath('language_to')
                ->addViolation();
        }
    }
}