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

namespace Betterthat\Betterthat\Block\Adminhtml\Profile\Edit\Tab\Attribute;

use Magento\Backend\Block\Widget;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

class CategoryJs extends Widget implements RendererInterface
{
    /**
     * @var string
     */
    public $_template = 'profile/category.phtml';
    /**
     * @var mixed|null
     */
    public $_profile;
    /**
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;
    /**
     * @var Betterthat
     */
    public $Betterthat;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Betterthat\Betterthat\Helper\Config $config
     * @param \BetterthatSdk\ProductFactory $Betterthat
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Betterthat\Betterthat\Helper\Config $config,
        \BetterthatSdk\ProductFactory $Betterthat,
        $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_profile = $this->_coreRegistry->registry('current_profile');
        $this->Betterthat = $Betterthat->create(['config' => $config->getApiConfig()]);
        parent::__construct($context, $data);
    }

    /**
     * Get current level of Betterthat category
     *
     * @param  integer $level
     * @return string
     */
    public function getLevel($level)
    {
        $option = [];
        /*$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $location = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getValue('Betterthat_config/Betterthat_setting/location');
        $locationList = $objectManager->get('Betterthat\Betterthat\Model\Config\Location')->toOptionArray();
        foreach ($locationList as $value) {
            if ($value['value'] == $location) {
                $locationName = $value['label'];
            }
        }
        $mediaDirectory = $objectManager->get('\Magento\Framework\Filesystem')
            ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::APP);
        $folderPath = $mediaDirectory->getAbsolutePath('code/Betterthat/Betterthat/Setup/json/');
        $path = $folderPath . $locationName . '/categoryLevel-' . $level . '.json';
        $rootlevel = $objectManager->get('Betterthat\Betterthat\Helper\Data')->loadFile($path);
        $options = isset($rootlevel['CategoryArray']['Category']) ? $rootlevel['CategoryArray']['Category'] : [];
        foreach ($options as $value) {
            if ($value['CategoryLevel'] == $level) {
                $option[] = $value;
            }
        }*/
        return $option;
    }

    /**
     * Render form element as HTML
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
}
