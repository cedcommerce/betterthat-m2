<?php

namespace Betterthat\Betterthat\Observer;

class Save implements \Magento\Framework\Event\ObserverInterface
{
    protected $objectManager;
    protected $productHelper;
    public $config;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Betterthat\Betterthat\Helper\Product $productHelper,
        \Betterthat\Betterthat\Model\ResourceModel\Profile $profileResource,
        \Betterthat\Betterthat\Model\ProfileFactory $profileFactory,
        \Betterthat\Betterthat\Helper\Config $config,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->objectManager = $objectManager;
        $this->productHelper = $productHelper;
        $this->profileResource = $profileResource;
        $this->profileModel = $profileFactory;
        $this->messageManager = $messageManager;
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isValid() == "0" || $this->config->isValid() == null) {
            return $observer;
        }
        if ($product = $observer->getEvent()->getProduct()) {
            try {
                if ($product->dataHasChangedFor('betterthat_profile_id') && $this->config->isValid()) {
                    if ($product->getbetterthat_profile_id()) {
                        $profileModel = $this->profileModel->create();
                        $this->profileResource->load($profileModel, $product->getbetterthat_profile_id(), 'id');
                        if ($profileModel->getId() == null) {
                            $message = __('Betterthat profile id is invalid,
                             please fill correct id and previous id has been reset.');
                            $this->messageManager->addWarningMessage($message);
                            $product->setbetterthat_profile_id($product->getOrigData('betterthat_profile_id'));
                        }
                    }
                }

                if ($product->dataHasChangedFor('betterthat_visibility') && $this->config->isValid()) {
                    $response = $this->productHelper
                        ->_sendBetterthatVisibility(
                            ["product_id" => $product->getId(),
                                "visible_status" => $product->getBetterthatVisibility() ? "true" : "false"
                            ]
                        );
                    if (isset($response['status']) && $response['status']) {
                        $this->messageManager->addSuccessMessage($response["message"]);
                    }
                }

            } catch (\Exception $e) {
                $e->getMessage();
            }
        }
    }
}
