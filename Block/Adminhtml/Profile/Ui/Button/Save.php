<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 15/1/18
 * Time: 1:39 PM
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Ui\Button;

use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Save extends GenericButton implements ButtonProviderInterface
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }

    /**
     * GetBackUrl
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/save');
    }
}
