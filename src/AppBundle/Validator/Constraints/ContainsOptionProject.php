<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Class ContainsOptionProject
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class ContainsOptionProject extends Constraint
{
    public $message = 'The options to "%string%" is not valid.';
}