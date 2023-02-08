<?php
namespace Betterthat\Betterthat\Model;

use Psr\Log\LoggerInterface;

class SubmitShipment
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var \Betterthat\Betterthat\Helper\Order
     */
    public $orderHelper;

    /**
     * Construct
     *
     * @param LoggerInterface $logger
     * @param \Betterthat\Betterthat\Helper\Order $orderHelper
     */
    public function __construct(
        LoggerInterface $logger,
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
            $response = $this->orderHelper->submitMagentoShipment($data);
        } catch (\Exception $e) {
            $response =  ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return [$response];
    }
}
