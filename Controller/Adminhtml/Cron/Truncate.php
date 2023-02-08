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

namespace Betterthat\Betterthat\Controller\Adminhtml\Cron;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

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

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
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
