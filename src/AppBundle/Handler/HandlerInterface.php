<?php


namespace AppBundle\Handler;


interface HandlerInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param array $options
     * @return mixed
     */
    public function all(array $options = []);

    /**
     * @param array $params
     * @return mixed
     */
    public function post(array $params);

    /**
     * @param $objectInterface
     * @param array $params
     * @return mixed
     */
    public function put($objectInterface, array $params);


    /**
     * @param $objectInterface
     * @return mixed
     */
    public function patch($objectInterface, array $params);

    /**
     * @param $objectInterface
     * @return mixed
     */
    public function delete($objectInterface);
}