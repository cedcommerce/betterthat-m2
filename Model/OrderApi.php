<?php
namespace Betterthat\Betterthat\Model;

use Psr\Log\LoggerInterface;

class OrderApi
{

    protected $logger;
    public $orderHelper;

    public function __construct(
        LoggerInterface $logger,
        \Betterthat\Betterthat\Helper\Order $orderHelper
    ) {
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
    }
    /**
     * @inheritdoc
     */
    public function getPost($data)
    {
        try {
            // Your Code here
            $response = $this->orderHelper->importOrders($data);
        } catch (\Exception $e) {
            $response =  ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return [$response];
    }
}
