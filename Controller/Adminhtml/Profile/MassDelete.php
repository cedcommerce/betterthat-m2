<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Controller\Adminhtml\Profile;

use \Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class MassDelete extends Action
{
    /**
     * @var
     */
    protected $profile;

    /**
     * @var
     */
    protected $profileResource;

    /**
     * @var
     */
    protected $collection;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * MassDelete constructor.
     *
     * @param Action\Context                                                $context
     * @param PageFactory                                                   $resultPageFactory
     * @param \Magento\Ui\Component\MassAction\Filter                       $filter
     * @param \Ced\Betterthat\Model\ProfileFactory                          $profile
     * @param \Ced\Betterthat\Model\ResourceModel\Profile                   $profileResource
     * @param \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collection
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\ProfileFactory $profile,
        \Ced\Betterthat\Model\ResourceModel\Profile $profileResource,
        \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $collection,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Ced\Betterthat\Helper\Config $config
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
                    $productIds = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create()
                        ->addAttributeToFilter('betterthat_profile_id', ['eq' => $item->getId()])
                        ->addAttributeToSelect('betterthat_profile_id')->getAllIds();
                    $storeId = $this->config->getStore();
                    $this->_prodAction->updateAttributes($productIds, ['betterthat_profile_id'=> null], $storeId);
                    $item->delete();
                }
                $this->messageManager->addSuccessMessage(__('Total of %1 record(s) have been deleted.', $collection->count()));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return $this->_redirect('*/*/index');
    }
}
