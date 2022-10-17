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

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Betterthat\Betterthat\Model\Profile;
use Magento\Backend\App\Action;

class Edit extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;
    /**
     * @var _entityTypeId
     */
    public $_entityTypeId;
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;
    /**
     * @var Profile
     */
    public $profile;
    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param Profile $profile
     * @param \Betterthat\Betterthat\Helper\Config $config
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        PageFactory $resultPageFactory,
        Profile $profile,
        \Betterthat\Betterthat\Helper\Config $config
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->profile = $profile;
        $this->config = $config;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        // Case 2.1 : Ui form
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Betterthat_Betterthat::Betterthat_profile');
        $id = $this->getRequest()->getParam('id');
        if (isset($id) && !empty($id)) {
            $this->profile->load($id);
            if ($this->profile && $this->profile->getData('profile_name')) {
                $this->_coreRegistry->register('Betterthat_profile', $this->profile);
                $resultPage->getConfig()->getTitle()
                    ->prepend(__('Edit Profile '.$this->profile->getData('profile_name')));
            } else {
                $resultPage->getConfig()->getTitle()->prepend(__('Add New Profile'));
            }
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Add New Profile'));
        }
        return $resultPage;
    }
}
