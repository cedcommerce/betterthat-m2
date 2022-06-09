<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Cron
 */
class Fetch extends Action
{
    /**
     * ResultPageFactory
     *
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * Ced\Betterthat\Helper\Category
     *
     * @var PageFactory
     */
    public $category;
    public $_coreRegistry;

    /**
     * Index constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Ced\Betterthat\Helper\Category $category
    ) {
        parent::__construct($context);
        $this->category = $category;
        $this->_coreRegistry = $registry;
        $this->session =  $context->getSession();
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getPost('id');
        $current_profile_id = $this->getRequest()->getPost('current_profile_id');
        $level = $this->getRequest()->getPost('level');
        $loadCatObj = $this->_objectManager
            ->create(\Ced\Betterthat\Model\Profile::class)->load($current_profile_id);
        $check = [];
        if (!empty($loadCatObj) && $loadCatObj->getId()) {
            $check = json_decode($loadCatObj->getData('profile_categories'), true);
        }
        $args = ['max_level'=> $level];
        if (!empty($id)) {
            $this->session->setCategoryM($id);
            $args['level'] = $level;
            $args['category_id'] = $id;
        } else {
            $args['level']='0';
            $args['category_id'] = $id;
        }
        $response = $this->category->getCategories($args);
        if (count($response) > 0) {
            $categoryHtml = '<option></option>';
            foreach ($response as $value) {
                        $categoryHtml .= '<option value="'.$value["_id"].'">'.$value["Name"].'</option>';
            }
            if ($categoryHtml=='<option></option>') {
                $this->getResponse()->setBody("Unable to fetch category");
            } else {
                $this->getResponse()->setBody($categoryHtml);
            }
        } else {
            $this->getResponse()->setBody("Unable to fetch category");
        }
    }
}
