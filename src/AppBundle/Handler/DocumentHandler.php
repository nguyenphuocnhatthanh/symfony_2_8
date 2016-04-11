<?php
namespace AppBundle\Handler;

use AppBundle\Exception\JsonNotValidException;
use AppBundle\Exception\TransformerException;
use AppBundle\Service\HandlerErrorsApi;
use AppBundle\Service\TextMasterService;
use AppBundle\Service\TransferArrayToObject;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DocumentHandler implements HandlerInterface
{
    private $form;

    private $validator;

    public function __construct(ValidatorInterface $validator, FormFactoryInterface $form)
    {
        $this->form = $form;
        $this->validator = $validator;
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
        $project_id = $params['project_id'];
//        $app_id = $params['app_id'];
        unset($params['project_id']);

        $transform = new TransferArrayToObject($params['TextMasterNewDocument']);

        try {
            $errors = $this->validator->validate($transform->transfer());

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

        try {
            $textMaster = new TextMasterService();
            $response = $textMaster->createDocument($project_id, json_encode($params['TextMasterNewDocument']));

            if (isset($response['errors'])) {
                $response_error = [
                    'code' => $response['code'],
                    'message' => '',
                    'fields' => ''
                ];

                foreach ($response['errors'] as $field => $error) {
                    $response_error['fields'] .= $field .', ';
                    $response_error['message'] .= $field .': '.$error[0]. '. ';
                }

                $response_error['fields'] = substr($response_error['fields'], 0, -2);

                return $response_error;
            }

            addTextMasterDateForArray($response);

            return [
                'TextMasterDocumentResponse' => [
                    'success' => TRUE,
                    'data' => [
                        'TextMasterDocumentRow' => $response
                    ]
                ]
            ];
        } catch (JsonNotValidException $e) {
            return [
                'code' => 500,
                'message' => $e->getMessage(),
                'fields' => null
            ];
        }
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