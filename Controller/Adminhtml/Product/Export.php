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

namespace Ced\Betterthat\Controller\Adminhtml\Product;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Price
 *
 * @package Ced\Betterthat\Controller\Adminhtml\Product
 */
class Export extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    public $filter;

    /**
     * @var \Ced\Betterthat\Helper\Product
     */
    public $Betterthat;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $catalogCollection;

    /**
     * @var \Ced\Betterthat\Helper\Config
     */
    public $config;

    public $session;

    public $registry;

    public $resultJsonFactory;

    public $resultPageFactory;

    public $filesystem;

    public $directory;

    public $fileFactory;

    /**
     * Export constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Catalog\Model\Product $collection
     * @param \Ced\Betterthat\Helper\Config $config
     * @param \BetterthatSdk\ProductFactory $Betterthat
     * @param \Magento\Framework\Filesystem $filesystem
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Catalog\Model\Product $collection,
        \Ced\Betterthat\Helper\Config $config,
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

        $collection = $this->productFactory->create()->addAttributeToSelect(['name','betterthat_validation_errors'])->addFieldToFilter('entity_id',['in'=>$productIds]);
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

        //return $this->_redirect('betterthat/product/index');
    }


    /**
     * @param $errorJson
     * @return false|mixed|string|null
     */
    public function fetchErrors($errorJson){
        $errors = json_decode($errorJson,1);

        if(@$errors[0] == 'valid')
            return $errors[0];
        if(!$errors){
            return $errors;
        }
        foreach($errors as $error){
            if(@$error['errors'][0])
                return implode(':',$error['errors'][0]);
            else
                return json_encode($error['errors'][0]);

        }
    }
}
