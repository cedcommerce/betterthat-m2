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

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Betterthat\Betterthat\Model\Profile;

class UpdateCategoryAttributes extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;
    /**
     * @var Profile
     */
    public $profile;
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     */
    public $category;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Profile $profile
     * @param \Betterthat\Betterthat\Helper\Category $category
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Profile $profile,
        \Betterthat\Betterthat\Helper\Category $category
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->profile = $profile;
        $this->category = $category;
    }

    /**
     * Execute
     *
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
                \Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\View\AttributeMapping::class,
                'Betterthat_attributes'
            )
            ->setAttributes($attributes)
            ->toHtml();
        return $this->getResponse()->setBody($result);
    }
}
