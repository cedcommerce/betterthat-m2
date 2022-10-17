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
 * @copyright Copyright Betterthat (https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab;

class Info extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\ObjectManagerInterface $objectInterface,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_objectManager = $objectInterface;
        parent::__construct($context, $registry, $formFactory);
    }

    /**
     * PrepareForm
     *
     * @return Info
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $profile = $this->_coreRegistry->registry('current_profile');

        $fieldset = $form->addFieldset('profile_info', ['legend' => __('Profile Information')]);

        $fieldset->addField(
            'profile_code',
            'text',
            [
                'name' => "profile_code",
                'label' => __('Profile Code'),
                'note' => __('For internal use. Must be unique with no spaces'),
                'class' => 'validate-code',
                'required' => true,
                'value' => $profile->getData('profile_code'),
            ]
        );

        $fieldset->addField(
            'profile_name',
            'text',
            [
                'name' => "profile_name",
                'label' => __('Profile Name'),
                'class' => '',
                'required' => true,
                'value' => $profile->getData('profile_name'),
            ]
        );

        $fieldset->addField(
            'profile_status',
            'select',
            [
                'name' => "profile_status",
                'label' => __('Profile Status'),
                'value' => $profile->getData('profile_status'),
                'values' => $this->_objectManager
                    ->get(\Betterthat\Betterthat\Model\Source\Profile\Status::class)
                    ->getOptionArray(),
            ]
        );

        if ($profile->getId()) {
            $form->getElement('profile_code')->setDisabled(1);
        }
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
