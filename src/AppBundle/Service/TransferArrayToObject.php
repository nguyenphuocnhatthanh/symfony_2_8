<?php
namespace AppBundle\Service;

use AppBundle\Exception\TransformerException;

/**
 * Class TransferArrayToObject
 * @package AppBundle\Service
 */
class TransferArrayToObject
{
    /**
     * @var array
     */
    private $dataArray;

    /**
     * TransferArrayToObject constructor.
     * @param $dataArray
     */
    public function __construct(array $dataArray)
    {
        $this->dataArray = $dataArray;
    }

    /**
     * Transfer data array to Object Entity
     * @return mixed
     * @throws TransformerException
     * @throws \Exception
     */
    public function transfer()
    {
        $mappings = $this->mapping();

        foreach ($this->dataArray as $object => $properties) {
            if (!isset($mappings[$object])) {
                throw new \Exception('Valid entity not mapping.');
            }

            $objectEntity = new $mappings[$object];
            foreach ($properties as $property => $val) {
                if (!method_exists($objectEntity, $this->camelCaseSetMethod($property))) {
                    throw new TransformerException($property);
                }

                $objectEntity->{$this->camelCaseSetMethod($property)}($val);
            }

            return $objectEntity;
        }
    }

    /**
     * Mapping class Entity
     * @return array
     */
    private function mapping()
    {
        return [
            'project' => '\AppBundle\Entity\Project',
            'document' => '\AppBundle\Entity\Document',
        ];
    }

    /**
     * Get method Setter
     * @param $str
     * @param array $noStrip
     * @return string
     */
    public function camelCaseSetMethod($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);

        $str = str_replace(" ", "", $str);

        return 'set'.$str;
    }
}