<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ContainsDocumentsProject extends Constraint
{
    public $message = 'The Documents not valid.';
}