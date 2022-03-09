<?php
namespace Ced\Betterthat\Api;


interface OrderInterface {


    /**
     * Post call to create/sync betterthat orders
     * @param mixed $data
     * @return mixed
     */

    public function getPost($data);
}
