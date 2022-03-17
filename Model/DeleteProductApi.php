<?php
namespace Ced\Betterthat\Model;

use Psr\Log\LoggerInterface;
class DeleteProductApi {

    protected $logger;
    public $productHelper;

    public function __construct(
        LoggerInterface $logger,
        \Ced\Betterthat\Helper\Product $productHelper
    )
    {
        $this->productHelper = $productHelper;
        $this->logger = $logger;
    }
    /**
     * @inheritdoc
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
