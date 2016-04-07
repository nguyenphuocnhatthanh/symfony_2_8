<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * Class ContainsLanguageToProject
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class ContainsLanguageToProject extends Constraint
{
    public $message = 'The language to "%string%" is not valid.';

    public $callback;

    public function getRequiredOptions()
    {
        return array('callback');
    }
}