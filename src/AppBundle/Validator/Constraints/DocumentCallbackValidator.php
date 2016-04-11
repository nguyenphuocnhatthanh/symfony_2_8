<?php
namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Document;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DocumentCallbackValidator
{
    /**
     * @param Document $object
     * @param ExecutionContextInterface $context
     */
    public static function validate(Document $object, ExecutionContextInterface $context)
    {

        if (!$object->getWordCountRule() && !$object->getWordCount()) {
            $context->buildViolation('The word count not valid.')
                ->atPath('word_count')
                ->addViolation();
        }
    }
}