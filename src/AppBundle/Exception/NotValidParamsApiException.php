<?php
namespace AppBundle\Exception;


use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class NotValidParamsApiException
 * @package AppBundle\Exception
 */
class NotValidParamsApiException extends \Exception
{
    const DEFAULT_ERROR_MESSAGE = "The data submitted was invalid.";

    /**
     * @var ConstraintViolationListInterface
     */
    protected $errors;

    /**
     * NotValidParamsApiException constructor.
     * @param string $message
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct($message = self::DEFAULT_ERROR_MESSAGE, ConstraintViolationListInterface $errors)
    {
        parent::__construct($message);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        $responseErrors = [];
        foreach ($this->errors as $error) {
            preg_match('/^\[(.*?)\]$/', $error->getPropertyPath(), $field);
            $responseErrors[end($field)] = $error->getMessage();
        }

        return [
            'code' => 400,
            'message' => 'Bad Request',
            'fields' => $responseErrors
        ];
    }
}