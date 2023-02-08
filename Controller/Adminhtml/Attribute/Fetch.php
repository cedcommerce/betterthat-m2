<?php
/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Controller\Adminhtml\Attribute;

class Fetch extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     */
    public $category;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Json\Helper\Data $json
     * @param \Betterthat\Betterthat\Helper\Category $category
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Json\Helper\Data $json,
        \Betterthat\Betterthat\Helper\Category $category
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->json = $json;
        $this->category = $category;
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $response = [
            'message' => [],
            'success' => false
        ];

        $data = $this->getRequest()->getParams();

        $attributes = [];
        if (isset($data['code']) && !empty($data['code'])) {
            $response['success'] = true;
            $attributesm = $this->category->getAttributes(['hierarchy' => $data['code'],'isMandatory' => 1]);
            $attributesr = $this->category->getAttributes(['hierarchy' => $data['code'],'isMandatory' => 0]);
            if (is_array($attributesm) && is_array($attributesr)) {
                $attributes = array_merge($attributesm, $attributesr);
            }
        }

        $response['attributes'] = $attributes;
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Betterthat_Betterthat::Betterthat_orders');
    }
}
