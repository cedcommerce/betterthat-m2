<?php
namespace Betterthat\Betterthat\Model;

class OrderApi
{
    /**
     * @var \Betterthat\Betterthat\Helper\Logger
     */
    protected $logger;
    /**
     * @var \Betterthat\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * Construct
     *
     * @param \Betterthat\Betterthat\Helper\Logger $logger
     * @param \Betterthat\Betterthat\Helper\Order $orderHelper
     */
    public function __construct(
        \Betterthat\Betterthat\Helper\Logger $logger,
        \Betterthat\Betterthat\Helper\Order $orderHelper
    ) {
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
    }

    /**
     * GetPost
     *
     * @param array $data
     * @return array[]
     */
    public function getPost($data)
    {
        try {
            // Your Code here
            $this->logger->info(
                'Betterthat Webhook Order Creation Data',
                [
                    'path' => __METHOD__,
                    'Data' => json_encode($data)
                ]
            );
            $response = $this->orderHelper->importOrders($data);
        } catch (\Exception $e) {
            $response =  ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info(
                'Betterthat Webhook Order Creation Exception : ' . $e->getMessage(),
                [
                    'path' => __METHOD__,
                    'Data' => json_encode($data)
                ]
            );
        }
        return [$response];
    }
}
