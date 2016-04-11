<?php
namespace AppBundle\Handler;

use AppBundle\Form\Type\ProjectType;
use AppBundle\Service\HandlerErrorsApi;
use AppBundle\Service\TextMasterService;
use AppBundle\Service\TransferArrayToObject;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
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

    private $form;

    private $projectType;

    private $om;

    /**
     * ProjectHandlerApi constructor.
     * @param ValidatorInterface $validate
     * @param FormFactoryInterface $factoryInterface
     * @param ProjectType $projectType
     * @param ObjectManager $om
     */
    public function __construct(ValidatorInterface $validate, FormFactoryInterface $factoryInterface, ProjectType $projectType, ObjectManager $om)
    {
        $this->validate = $validate;
        $this->form = $factoryInterface;
        $this->projectType = $projectType;
        $this->om = $om;
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
        $params['TextMasterNewProject']['project'] = array_merge($this->setDefaultParams(), $params['TextMasterNewProject']['project']);
        $transfer = new TransferArrayToObject($params['TextMasterNewProject']);

        /**@var $errors Get errors when validate**/
        $errors = $this->validate->validate($transfer->transfer());

        if (count($errors) > 0) {
            return (new HandlerErrorsApi($errors))->getErrors();
        }

        /** @Object $testMaster **/
        $textMaster = new TextMasterService();
        $response_project = $textMaster->createProject(json_encode($params['TextMasterNewProject']));

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

    private function setDefaultParams()
    {
        return [
            'vocabulary_type' => 'not_specified',
            'grammatical_person' => 'not_specified',
            'target_reader_groups' => 'not_specified',
            'options' => [
                'language_level' => 'premium'
            ]
        ];
    }
}