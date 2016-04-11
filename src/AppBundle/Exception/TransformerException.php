<?php
namespace AppBundle\Exception;

/**
 * Class TransformerException
 * @package AppBundle\Exception
 */
class TransformerException extends \Exception
{
    /**
     * @var string
     */
    private $field;

    /**
     * TransformerException constructor.
     * @param string $field
     */
    public function __construct($field)
    {
        $this->field = $field;

        parent::__construct(sprintf('Field %s not valid', $field));
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}