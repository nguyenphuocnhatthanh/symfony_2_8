<?php
namespace AppBundle\Exception;

/**
 * Class TableNotMappingException
 * @package AppBundle\Exception
 */
class TableNotMappingException extends \Exception
{
    const DEFAULT_ERROR_MESSAGE = "The table name not mapping.";

    /**
     * TableNotMappingException constructor.
     * @param string $message
     */
    public function __construct($message = self::DEFAULT_ERROR_MESSAGE)
    {
        parent::__construct($message);
    }
}