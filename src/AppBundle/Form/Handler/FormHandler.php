<?php


namespace AppBundle\Form\Handler;


use AppBundle\Form\FormErrorsSerializer;
use AppBundle\Form\FormInvalidException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormHandler
{
    private $em;
    private $formFactory;
    private $formType;
    private $formErrorSerializer;

    public function __construct(ObjectManager $objectManager, FormFactoryInterface $factoryInterface, FormTypeInterface $formTypeInterface, FormErrorsSerializer $formErrorsSerializer)
    {
        $this->em = $objectManager;
        $this->formFactory = $factoryInterface;
        $this->formType = $formTypeInterface;
        $this->formErrorSerializer = $formErrorsSerializer;
    }

    public function processForm($object, array $params, $method)
    {
        $form = $this->formFactory->create($this->formType, $object, [
            'method' => $method,
            'csrf_protection' => FALSE
        ]);

        $form->submit($params, 'PATCH' !== $method);


        if (!$form->isValid()) {
//            return $this->formError->serializeFormErrors($form, TRUE, TRUE);
            throw new FormInvalidException($form, FormInvalidException::DEFAULT_ERROR_MESSAGE, $this->formErrorSerializer);
        }

        $data = $form->getData();
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    public function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();

        return TRUE;
    }
}