<?php
namespace AppBundle\Exception;

class JsonNotValidException extends \Exception
{
    const DEFAULT_ERROR_MESSAGE = "The data is not valid json type";

    /**
     * TableNotMappingException constructor.
     * @param string $message
     */
    public function __construct($message = self::DEFAULT_ERROR_MESSAGE)
    {
        parent::__construct($message);
    }
}