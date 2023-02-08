<?php
namespace Betterthat\Betterthat\Api;

interface DeleteProductInterface
{
    /**
     * Post call to submit shipment for betterthat orders
     *
     * @param  mixed $data
     * @return mixed
     */
    public function getPost($data);
}
