<?php
namespace AppBundle\Service;

use AppBundle\Exception\TableNotMappingException;
use AppBundle\Validator\Constraints\ChoiceAllowNull;
use AppBundle\Validator\Constraints\ContainsCategory;
use AppBundle\Validator\Constraints\ContainsLanguageToProject;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class ApiValidate
 * @package AppBundle\Service
 */
class ApiValidate
{

    /**
     * @var
     */
    private $table;

    /**
     * @var array
     */
    private $options = [];

    /**
     * ApiValidate constructor.
     * @param $table
     * @param array $options
     */
    public function __construct($table, array $options)
    {
        $this->table =  $table;

        $this->options = array_merge($this->options, $options);

    }

    /**
     * @return Collection
     * @throws TableNotMappingException
     */
    public function rules()
    {
        $maps = $this->mappings();

        if (!isset($maps[$this->table])) {
            throw new TableNotMappingException;
        }

        $this->options = array_merge(['fields' => $maps[$this->table]], $this->options);

        return new Collection($this->options);
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
            ],
            'project_filter' => [
                'ref' => null,
                'name' => null,
                'ctype' => new Assert\Choice([
                    'choices' => ['copywriting', 'proofreading', 'translation'],
                    'message' => 'The activity name not valid.'
                ]),
                'archived' => new Assert\Type([
                    'type' => 'boolean',
                    'message' => 'The archived is not valid.'
                ]),
                'status' => new Assert\Choice([
                    'choices' => ['in_creation', 'in_progress', 'in_review', 'completed', 'paused', 'canceled'],
                    'message' => 'The status value id not valid.'
                ]),
                'created_at' => new Assert\DateTime([
                    'message' => 'The created at is not valid'
                ]),
                'updated_at' => new Assert\DateTime([
                    'message' => 'The update at is not valid'
                ]),
                'launched_at' => new Assert\DateTime([
                    'message' => 'The launch at is not valid'
                ]),
                'completed_at' => new Assert\DateTime([
                    'message' => 'The completed at is not valid'
                ]),
                'cached_documents_count' => new Assert\Type([
                    'type' => 'integer',
                    'message' => 'The cached documents count is not valid.'
                ]),
                'language_from_code' => new ContainsLanguageToProject(),
                'language_from_to' => new ContainsLanguageToProject(),
                'level_name' => new Assert\Choice([
                    'choices' => ['regular', 'premium', 'enterprise'],
                    'message' => 'The level name value id not valid.'
                ]),
                'pricing.total_cost_at_launch_time' => new Assert\Type([
                    'type' => 'integer',
                    'message' => 'The price total is not valid.'
                ]),
                'total_word_count' => new Assert\Type([
                    'type' => 'integer',
                    'message' => 'The total word count is not valid.'
                ]),
                'progress' => new Assert\Type([
                    'type' => 'integer',
                    'message' => 'The progress is not valid.'
                ]),
                'category' => new ContainsCategory()
            ]
        ];
    }

    private function removeUnNecessaryParams(array $params)
    {
        
    }
}