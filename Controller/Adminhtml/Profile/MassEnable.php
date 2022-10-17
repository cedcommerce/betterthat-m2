<?php
namespace Betterthat\Betterthat\Controller\Adminhtml\Profile;

class MassEnable extends \Magento\Backend\App\Action
{

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $profileIds = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded', false);
        if (!is_array($profileIds) && !$excluded) {
            $this->messageManager->addErrorMessage(__('Please select Profile(s).'));
        } elseif ($excluded == "false") {
            $profileIds  = $this->_objectManager
                ->create(\Betterthat\Betterthat\Model\Profile::class)
                ->getCollection()->getAllIds();
        }

        if (!empty($profileIds)) {
            try {
                foreach ($profileIds as $profileId) {
                    $profile = $this->_objectManager->create(\Betterthat\Betterthat\Model\Profile::class)
                        ->load($profileId);
                    $profile->setProfileStatus(1);
                    $profile->save();
                }
                $this->messageManager->addSuccessMessage(
                    __('Total of %1 record(s) have been enabled.', count($profileIds))
                );
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
