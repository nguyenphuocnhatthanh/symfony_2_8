<?php
namespace AppBundle\Handler;

use AppBundle\Entity\Project;
use AppBundle\Exception\HandlerErrorsApi;
use AppBundle\Service\TextMasterService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ProjectHandler
 * @package AppBundle\Handler
 */
class ProjectHandler implements HandlerInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validate;

    /**
     * ProjectHandlerApi constructor.
     * @param $validate
     */
    public function __construct(ValidatorInterface $validate)
    {
        $this->validate = $validate;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function all(array $options = [])
    {
        // TODO: Implement all() method.
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function post(array $params)
    {
        $data = &$params['project'];

        $default_project_data = [
            'vocabulary_type' => 'not_specified',
            'grammatical_person' => 'not_specified',
            'target_reader_groups' => 'not_specified',
            'options' => [
                'language_level' => 'premium'
            ]
        ];

        $data = array_merge($default_project_data, $data);

        $project = new Project();
        $project->createProject(
            !empty($data['name']) ? $data['name'] : '',
            !empty($data['activity_name']) ? $data['activity_name'] : '',
            !empty($data['options']) ? $data['options'] : [],
            $data['language_from'],
            $data['language_to'],
            $data['category'],
            $data['project_briefing'],
            $data['deadline'],
            'test',
            $data['author_should_use_rich_text'],
            '',
            $data['vocabulary_type'],
            $data['grammatical_person'],
            $data['target_reader_groups'],
            [],
            []
        );

        /**@var $errors Get errors when validate**/
        $errors = $this->validate->validate($project);

        if (count($errors) > 0) {
            return (new HandlerErrorsApi($errors))->getErrors();
        }

        $textmaster = new TextMasterService();
        $response_project = $textmaster->createProject(json_encode($params));

        if (isset($response_project['errors'])) {
            $response_error = [
                'code' => $response_project['code'],
                'message' => '',
                'fields' => ''
            ];

            foreach ($response_project['errors'] as $field => $error) {
                $response_error['fields'] .= $field .', ';
                $response_error['message'] .= $field .': '.$error[0]. '. ';
            }

            $response_error['fields'] = substr($response_error['fields'], 0, -2);

            return $response_error;
        }
        unset($response_project['code']);

        // Remove not unnecessary
        if (array_key_exists('callback', $response_project)) {
            unset($response_project['callback']);
        }

        if (array_key_exists('total_costs', $response_project)) {
            unset($response_project['total_costs']);
        }
        addObjectKeyForArray($response_project, 'documents_statuses', 'TextMasterDocumentsStatuses');
        addObjectKeyForArray($response_project, 'cost_per_word', 'TextMasterCost');
        addObjectKeyForArray($response_project, 'cost_in_currency', 'TextMasterCost');
        addTextMasterDateForArray($response_project);

        return [
            'TextMasterProjectResponse' => [
                'success' => true,
                'data' => [
                    'TextMasterProjectRow' => $response_project
                ]
            ]
        ];
    }

    /**
     * @param $objectInterface
     * @param array $params
     * @return mixed
     */
    public function put($objectInterface, array $params)
    {
        // TODO: Implement put() method.
    }

    /**
     * @param $objectInterface
     * @return mixed
     */
    public function patch($objectInterface, array $params)
    {
        // TODO: Implement patch() method.
    }

    /**
     * @param $objectInterface
     * @return mixed
     */
    public function delete($objectInterface)
    {
        // TODO: Implement delete() method.
    }
}