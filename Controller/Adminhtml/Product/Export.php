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

namespace Betterthat\Betterthat\Controller\Adminhtml\Product;

use Magento\Framework\App\Filesystem\DirectoryList;

class Export extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Betterthat\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $catalogCollection;

    /**
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;
    /**
     * @var \Magento\Backend\Model\Session
     */
    public $session;
    /**
     * @var registry
     */
    public $registry;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;
    /**
     * @var resultPageFactory
     */
    public $resultPageFactory;
    /**
     * @var filesystem
     */
    public $filesystem;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    public $directory;
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    public $fileFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Catalog\Model\Product $collection
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \BetterthatSdk\ProductFactory $Betterthat
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $product
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Betterthat\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $Betterthat,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $product,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filter = $filter;
        $this->catalogCollection = $collection;
        $this->Betterthat = $Betterthat;
        $this->config = $config;
        $this->session = $context->getSession();
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->productFactory = $product;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Execute
     *
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $productIds = $this->session->getBetterthatProducts();
        $filepath = 'export/validationErrors.csv';
        $filename = 'validationErrors.csv';
        $this->directory->create('export');
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        $header = ['Id', 'Name','Sku','Error'];
        $stream->writeCsv($header);
        $collection = $this->productFactory
            ->create()
            ->addAttributeToSelect(['name','betterthat_validation_errors'])
            ->addFieldToFilter('entity_id', ['in'=>$productIds]);
        foreach ($collection as $product) {
            $data = [];
            $data[] = $product->getId();
            $data[] = $product->getName();
            $data[] = $product->getSku();
            $data[] = $this->fetchErrors($product->getBetterthatValidationErrors());
            $stream->writeCsv($data);
        }
        $this->messageManager->addSuccessMessage('Validation Report Exported Successfully');
        return $this->fileFactory->create(
        //File name you would like to download it by
            $filename,
            [
                'type'  => "filename", //type has to be "filename"
                'value' => "export/{$filename}", // path will append to the
                // base dir
                'rm'    => true, // add this only if you would like the file to be
                // deleted after being downloaded from server
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
        );
    }

    /**
     * FetchErrors
     *
     * @param  string $errorJson
     * @return false|mixed|string|null
     */
    public function fetchErrors($errorJson)
    {
        if (empty($errorJson)) {
            return $errorJson;
        }
        $errors = json_decode($errorJson, 1);
        if (isset($errors[0]) && $errors[0] == 'valid') {
            return $errors[0];
        }
        if (!$errors) {
            return $errors;
        }
        foreach ($errors as $error) {
            if (isset($error['errors'][0])) {
                return implode(':', $error['errors'][0]);
            } else {
                return json_encode(isset($error['errors'][0]) ? $error['errors'][0] : []);
            }
        }
    }
}
