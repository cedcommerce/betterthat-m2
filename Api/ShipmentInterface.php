<?php
namespace Ced\Betterthat\Api;


interface ShipmentInterface
{


    /**
     * Post call to submit shipment for betterthat orders
     *
     * @param  mixed $data
     * @return mixed
     */

    public function getPost($data);
}
