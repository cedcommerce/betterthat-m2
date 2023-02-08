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

namespace Betterthat\Betterthat\Block\Adminhtml\Category;

class Index extends \Magento\Backend\Block\Template
{
    /**
     * @var \Betterthat\Betterthat\Helper\Category
     */
    public $category;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Betterthat\Betterthat\Helper\Category $category
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Betterthat\Betterthat\Helper\Category $category,
        $data = []
    ) {
        $this->category = $category;
        parent::__construct($context, $data);
    }

    /**
     * GetCategories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->category->getCategories(['level'=> 0]);
    }
}
