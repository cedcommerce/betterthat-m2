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

use Ced\Betterthat\Helper\Logger;

/**
 * Class MassDelete
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Profile
 */
class AssignProducts extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Ced_Betterthat::Betterthat';

    /**
     * @var \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $profileCollection
     */
    public $profileCollection;

    /**
     * @var \Ced\Betterthat\Helper\Profile
     */
    protected $profileHelper;

    /**
     * @var Logger \Ced\Betterthat\Helper\Logger
     */
    public $logger;

    public $filter;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Ced\Betterthat\Model\ResourceModel\Profile\CollectionFactory $profile,
        \Ced\Betterthat\Helper\Profile $profileHelper,
        \Ced\Betterthat\Helper\Logger $logger
    ) {
        parent::__construct($context);
        $this->profileCollection = $profile;
        $this->filter = $filter;
        $this->profileHelper = $profileHelper;
        $this->logger = $logger;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $isFilter = $this->getRequest()->getParam('filters');
        $profileIds = [];
        if (isset($id) && !empty($id)) {
            $profileIds[] = $id;
        } elseif (isset($isFilter)) {
            $collection = $this->filter
                ->getCollection($this->profileCollection->create());
            $profileIds = $collection->getAllIds();
        }
        if (!empty($profileIds)) {
            try {
                $updatedProfile = [];
                $profileColl = $this->profileCollection
                    ->create()->addFieldToFilter('id', ['in' => $profileIds]);
                foreach ($profileColl as $profile) {
                    $profile->addProducts($profile->getMagentoCategory());
                    $updatedProfile[] = $profile->getId();
                }
                $this->messageManager
                    ->addSuccessMessage(
                        __('Total of %1 profile(s) have been updated.', count($updatedProfile))
                    );
            } catch (\Exception $e) {
                $this->logger->addError(
                    'In Mass Assign Products Profile: '
                    .$e->getMessage(),
                    ['path' => __METHOD__]
                );
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            '*/*/index'
        );
    }
}
