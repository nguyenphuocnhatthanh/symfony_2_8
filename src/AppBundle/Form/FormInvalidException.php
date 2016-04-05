<?php
namespace AppBundle\Form;

class FormInvalidException extends \Exception
{
    const DEFAULT_ERROR_MESSAGE = "The data submitted to the form was invalid.";

    protected $form;

    protected $formErrors;

    /**
     * @param null $form
     * @param string $message
     * @param FormErrorsSerializer $formErrorsSerializer
     * @internal param FormInvalidException $formInvalidException
     */
    public function __construct($form = null, $message = self::DEFAULT_ERROR_MESSAGE, FormErrorsSerializer $formErrorsSerializer)
    {
        parent::__construct($message);

        $this->form = $form;
        $this->formErrors = $formErrorsSerializer;
    }

    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->formErrors->serializeFormErrors($this->form);
    }
}