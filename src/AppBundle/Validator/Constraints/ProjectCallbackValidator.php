<?php
namespace AppBundle\Validator\Constraints;


use AppBundle\Entity\Project;
use AppBundle\Service\TransferArrayToObject;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    public static function validateDocument(Project $object, ExecutionContextInterface $context)
    {
       /* if (!empty($object->getDocuments())) {

            foreach ($object->getDocuments() as $document) {

                $document = ['document' => $document['TextMasterDocumentRow']];


                $transform = new TransferArrayToObject($document);
//                $errors = $validate->validate($transform);

                die(dump($validate));
            }
        }*/
    }
}