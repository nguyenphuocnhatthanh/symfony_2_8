<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ContainsWordCountDocument
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class ContainsWordCountDocument extends Constraint
{
    public $message = 'The word count is not valid';
}