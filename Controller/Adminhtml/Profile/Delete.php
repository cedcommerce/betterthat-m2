<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * You can check the licence at this URL: https://cedcommerce.com/license-agreement.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com >
 * @copyright Copyright CEDCOMMERCE (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Controller\Adminhtml\Profile;

class Delete extends \Magento\Customer\Controller\Adminhtml\Group
{
    protected $_objectManager;

    protected $_session;

    /**
     * Delete the Attribute
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model = $this->_objectManager->create(\Ced\Betterthat\Model\Profile::class)->load($id);
            $productIds = $this->_objectManager
                ->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
                ->create()
                ->addAttributeToFilter('betterthat_profile_id', ['eq' => $id])
                ->addAttributeToSelect('betterthat_profile_id')->getAllIds();

            $storeId = $this->_objectManager
                ->create(\Ced\Betterthat\Helper\Config::class)->getStore();
            $this->_objectManager
                ->get(\Magento\Catalog\Model\ResourceModel\Product\Action::class)
                ->updateAttributes($productIds, ['betterthat_profile_id'=> null], $storeId);
            // entity type check
            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the profile.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect = $this->resultFactory->create('redirect');
                return $resultRedirect->setPath(
                    'betterthat/profile/edit',
                    ['pcode' => $this->getRequest()->getParam('pcode')]
                );
                //End
            }
        }
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            'betterthat/profile/index'
        );
    }
}
