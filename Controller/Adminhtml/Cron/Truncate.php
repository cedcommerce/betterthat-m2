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

namespace Ced\Betterthat\Controller\Adminhtml\Cron;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Truncate
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Cron
 */
class Truncate extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule\Collection
     */
    public $cron;

    /**
     * Truncate constructor.
     *
     * @param Action\Context                                        $context
     * @param PageFactory                                           $resultPageFactory
     * @param \Magento\Cron\Model\ResourceModel\Schedule\Collection $cron
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Cron\Model\ResourceModel\Schedule\Collection $cron
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->cron = $cron;
    }

    public function execute()
    {
        // Delete Crons from db
        $collection = $this->cron->addFieldToFilter(['job_code'], [[ 'like' => "%ced_%"]]);
        $collection->walk('delete');
        $this->messageManager->addSuccessMessage('Crons deleted successfully.');
        $resultRedirect = $this->resultFactory->create('redirect');
        return $resultRedirect->setPath(
            '*/*'
        );
    }
}
