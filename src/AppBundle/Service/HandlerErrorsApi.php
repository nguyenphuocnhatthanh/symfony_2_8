<?php
namespace AppBundle\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class HandlerErrorsApi
{
    protected $errors;

    /**
     * @param ConstraintViolationListInterface $errors
     * @internal param string $message
     */
    public function __construct(ConstraintViolationListInterface $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        $responseErrors = [];
        foreach ($this->errors as $key => $error) {
            $responseErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        return [
            'code' => 400,
            'message' => 'Bad Request',
            'fields' => $responseErrors
        ];
    }
}