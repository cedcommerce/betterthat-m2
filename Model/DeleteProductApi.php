<?php
namespace Betterthat\Betterthat\Model;

use Psr\Log\LoggerInterface;

class DeleteProductApi
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    public $productHelper;

    /**
     * DeleteProductApi constructor.
     *
     * @param LoggerInterface $logger
     * @param \Betterthat\Betterthat\Helper\Product $productHelper
     */
    public function __construct(
        LoggerInterface $logger,
        \Betterthat\Betterthat\Helper\Product $productHelper
    ) {
        $this->productHelper = $productHelper;
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
            $response = $this->productHelper->deleteProduct($data);
        } catch (\Exception $e) {
            $response =  ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return [$response];
    }
}
