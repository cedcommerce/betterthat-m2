<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Profile;

use \Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class MassDelete extends Action
{
    /**
     * @var profile
     */
    protected $profile;

    /**
     * @var profileResource
     */
    protected $profileResource;

    /**
     * @var collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Betterthat\Betterthat\Model\ProfileFactory $profile
     * @param \Betterthat\Betterthat\Model\ResourceModel\Profile $profileResource
     * @param \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collection
     * @param \Magento\Catalog\Model\ResourceModel\Product\Action $productAction
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Betterthat\Betterthat\Model\ProfileFactory $profile,
        \Betterthat\Betterthat\Model\ResourceModel\Profile $profileResource,
        \Betterthat\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collection,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->profile = $profile->create();
        $this->profileResource = $profileResource;
        $this->filter = $filter;
        $this->collection = $collection->create();
        $this->_prodAction = $productAction;
        $this->config = $config;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $collection = null;
        $isFilter = $this->getRequest()->getParam('filters');
        if (isset($isFilter)) {
            $collection = $this->filter->getCollection($this->collection);
        }

        if ($collection) {
            try {
                foreach ($collection as $item) {
                    $productIds = $this->_objectManager
                        ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
                        ->create()
                        ->addAttributeToFilter('betterthat_profile_id', ['eq' => $item->getId()])
                        ->addAttributeToSelect('betterthat_profile_id')->getAllIds();
                    $storeId = $this->config->getStore();
                    $this->_prodAction->updateAttributes($productIds, ['betterthat_profile_id'=> null], $storeId);
                    $item->delete();
                }
                $this->messageManager
                    ->addSuccessMessage(__('Total of %1 record(s) have been deleted.', $collection->count()));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            '*/*/index'
        );
    }
}
