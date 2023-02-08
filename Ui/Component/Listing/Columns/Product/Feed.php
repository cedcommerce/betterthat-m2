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
 * @copyright Copyright BETTERTHAT (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Ui\Component\Listing\Columns\Product;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Feed extends Column
{
    /**
     * @var UrlInterface
     */
    public $urlBuilder;

    /**
     * @var productHelper
     */
    public $productHelper;

    /**
     * Json Parser
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $json;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param \Magento\Framework\Json\Helper\Data $json
     * @param \Betterthat\Betterthat\Helper\Product $productHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Magento\Framework\Json\Helper\Data $json,
        \Betterthat\Betterthat\Helper\Product $productHelper,
        $components = [],
        $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->json = $json;
        $this->productHelper = $productHelper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$fieldName . '_child_product_status'] = json_encode(['Child Status' => 'No data available.']);
                $class = 'grid-severity-notice';
                $status = \Betterthat\Betterthat\Model\Source\Product\Status::NOT_UPLOADED;
                if (isset($item[$fieldName])) {
                    if (isset($item['betterthat_product_status']) && !empty($item['betterthat_product_status'])) {
                        $status = $item['betterthat_product_status'];
                        if ($status == \Betterthat\Betterthat\Model\Source\Product\Status::INVALID) {
                            $class = 'grid-severity-minor';
                        }
                    }

                    $item[$fieldName . '_html'] = "<button style='width:100%' class='{$class}'>"
                        ."<span>{$status}</span></button>";
                    $item[$fieldName . '_title'] = __('Betterthat Feed Details');
                    $item[$fieldName . '_productid'] = $item['entity_id'];
                    if (isset($item['betterthat_feed_errors'])) {
                        $item[$fieldName . '_product_feed_errors'] = $item['betterthat_feed_errors'];
                    } else {
                        $item[$fieldName . '_product_feed_errors'] =
                            json_encode(['Data' => 'No data available.']);
                    }
                } else {
                    $item[$fieldName . '_html'] = "<button style='width:100%' class='{$class}'>"
                        ."<span>{$status}</span></button>";
                    $item[$fieldName . '_title'] = __('Betterthat Feed Details');
                    $item[$fieldName . '_productid'] = $item['entity_id'];
                    if (isset($item['betterthat_feed_errors'])) {
                        $item[$fieldName . '_product_feed_errors'] = $item['betterthat_feed_errors'];
                    } else {
                        $item[$fieldName . '_product_feed_errors']
                            = json_encode(['Data' => 'No data available.']);
                    }
                }
            }
        }
        return $dataSource;
    }
}
