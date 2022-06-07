<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Betterthat
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (https://cedcommerce.com/)
 * @license   https://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Betterthat\Ui\Component\Listing\Columns\Order;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Actions extends Column
{
    /**
 * Url path
*/
    public const URL_PATH_EDIT = 'sales/order/view';
    public const URL_PATH_VIEW = 'betterthat/order/view';
    public const URL_PATH_SYNC = 'betterthat/order/sync';
    public const URL_PATH_DELETE = 'betterthat/order/delete';
    public const URL_PATH_DOWNLOAD = 'betterthat/order/download';

    /**
     * @var UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Actions constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_EDIT,
                            [
                                'order_id' => $item['magento_order_id']
                            ]
                        ),
                        'label' => __('Edit'),
                        'class' => 'cedcommerce actions edit'
                    ];
                    $item[$name]['sync'] = [
                        'href' => $this->urlBuilder
                            ->getUrl(self::URL_PATH_SYNC, ['id' => $item['id']]),
                        'label' => __('Sync'),
                        'class' => 'cedcommerce actions sync'
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder
                            ->getUrl(self::URL_PATH_DELETE, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'class' => 'cedcommerce actions delete'
                    ];
                }
            }
        }
        return $dataSource;
    }
}
