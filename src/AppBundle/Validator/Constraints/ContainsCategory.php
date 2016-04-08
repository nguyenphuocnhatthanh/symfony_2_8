<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ContainsCategory
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class ContainsCategory extends Constraint
{
    public $message = 'The category to "%string%" is not valid.';
}