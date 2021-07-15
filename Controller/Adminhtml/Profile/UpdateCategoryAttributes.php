<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Controller\Adminhtml\Profile;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Ced\Betterthat\Model\Profile;

/**
 * Class UpdateCategoryAttributes
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Profile
 */
class UpdateCategoryAttributes extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    public $profile;

    public $category;

    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Profile $profile,
        \Ced\Betterthat\Helper\Category $category
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->profile = $profile;
        $this->category = $category;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $categoryIds = $this->getRequest()->getParam('id');
        $params = [
            'hierarchy' => $categoryIds,
            'isMandatory' => 1
        ];

        $requiredAttributes = $this->category->getAttributes($params);

        $params = [
            'hierarchy' => $categoryIds,
            'isMandatory' => 0
        ];
        $optionalAttributes = $this->category->getAttributes($params);

        $attributes[] = [
            'label' => 'Required Attributes',
            'value' => $requiredAttributes
        ];
        $attributes[] = [
            'label' => 'Optional Attributes',
            'value' => $optionalAttributes
        ];

        $result = $this->resultPageFactory->create(true)
            ->getLayout()->createBlock(
                'Ced\Betterthat\Block\Adminhtml\Profile\Ui\View\AttributeMapping',
                'Betterthat_attributes'
            )
            ->setAttributes($attributes)
            ->toHtml();
        return $this->getResponse()->setBody($result);
    }
}
