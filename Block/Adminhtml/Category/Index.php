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

namespace Ced\Betterthat\Block\Adminhtml\Category;

class Index extends \Magento\Backend\Block\Template
{
    public $category;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Ced\Betterthat\Helper\Category $category,
        $data = []
    ) {
        $this->category = $category;
        parent::__construct($context, $data);
    }

    public function getCategories()
    {
        return $this->category->getCategories(['level'=> 0]);
    }
}
