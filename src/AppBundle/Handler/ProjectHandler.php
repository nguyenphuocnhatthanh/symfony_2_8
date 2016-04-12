<?php
namespace AppBundle\Handler;

use AppBundle\Form\Type\ProjectType;
use AppBundle\Service\ApiValidate;
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

        foreach ($params['TextMasterNewProject']['project']['documents'] as $document) {
            $transferDocument = new TransferArrayToObject(['document' => $document['TextMasterDocumentRow']]);
            try {
                $errors = $this->validate->validate($transferDocument->transfer());

                if (count($errors)) {
                    return (new HandlerErrorsApi($errors))->getErrors();
                }
            } catch (TransformerException $e) {
                return [
                    'code' => 400,
                    'message' => $e->getMessage(),
                    'fields' => $e->getField()
                ];
            }

        }


        /** @Object $testMaster **/
        $textMaster = new TextMasterService();
        $response = $textMaster->createProject(json_encode($params['TextMasterNewProject']));


        if (isset($response['code']) && 200 !== $response['code']) {
            return $response;
        }

        // Remove not unnecessary
        if (array_key_exists('callback', $response)) {
            unset($response['callback']);
        }

        if (array_key_exists('total_costs', $response)) {
            unset($response['total_costs']);
        }
        addObjectKeyForArray($response, 'documents_statuses', 'TextMasterDocumentsStatuses');
        addObjectKeyForArray($response, 'cost_per_word', 'TextMasterCost');
        addObjectKeyForArray($response, 'cost_in_currency', 'TextMasterCost');
        addTextMasterDateForArray($response);

        return [
            'TextMasterProjectResponse' => [
                'success' => true,
                'data' => [
                    'TextMasterProjectRow' => $response
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

    public function filter(array $params)
    {
        $where = json_decode($params['where'], true);

        if (!$where) {
            return [
                'code' => 400,
                'message' => 'The operator where is not valid',
                'fields' => null
            ];
        }

        $rules = new ApiValidate('project_filter', ['allowMissingFields' => true, 'allowExtraFields' => true]);
        $errors = $this->validate->validate($where, $rules->rules());

        if (count($errors) > 0) {
            return (new HandlerErrorsApi($errors))->getErrors();
        }

        /** @Object $testMaster **/
        $textMaster = new TextMasterService();

        $response = $textMaster->filterProject('?where='.urlencode($params['where']).'&order='.$params['order']);

        if (isset($response['code']) && 200 !== $response['code']) {
            return $response;
        }

        return [
            'success' => true,
            'total' => count($response),
            'rows' => [
                'TextMasterProjectRow' => $response
            ]
        ];
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