<?php


namespace AppBundle\Form\Handler;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormHandler
{
    private $em;
    private $formFactory;
    private $formType;

    public function __construct(ObjectManager $objectManager, FormFactoryInterface $factoryInterface, FormTypeInterface $formTypeInterface)
    {
        $this->em = $objectManager;
        $this->formFactory = $factoryInterface;
        $this->formType = $formTypeInterface;
    }

    public function processForm($object, array $params, $method)
    {
        $form = $this->formFactory->create($this->formType, $object, [
            'method' => $method,
            'csrf_protection' => FALSE
        ]);

        $form->submit($params, 'PATCH' !== $method);

        if (!$form->isValid()) {
            return $form->getErrors();
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