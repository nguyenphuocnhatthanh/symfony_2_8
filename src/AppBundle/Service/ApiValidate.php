<?php
namespace AppBundle\Service;

use AppBundle\Exception\TableNotMappingException;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class ApiValidate
 * @package AppBundle\Service
 */
class ApiValidate
{

    /**
     * @param $table string
     * @return Collection
     * @throws TableNotMappingException
     */
    public function rules($table)
    {
        $maps = $this->mappings();

        if (!isset($maps[$table])) {
            throw new TableNotMappingException;
        }

        return new Collection($maps[$table]);
    }

    /**
     * @return array
     */
    public function mappings()
    {
        return
            [
                'project' => [
                    'name' => [
                        new NotBlank()
                    ],
                    'activity_name' => [
                        new Assert\Choice([
                            'choices' => ['copywriting', 'proofreading', 'translation'],
                            'message' => 'The activity name not valid.'
                        ])
                    ],
                    'options' => [
                        new NotBlank()
                    ],
                    'language_to' => [
                        new NotBlank()
                    ],
                    'category' => [
                        new NotBlank()
                    ],
                    'project_briefing' => [
                        new NotBlank()
                    ],
                    'documents' => [
                        new NotBlank()
                    ],
                    'vocabulary_type' => null,
                    'target_reader_groups' => null,
                    'grammatical_person' => null,
                    'language_from' => null,
            ]
        ];
    }
}