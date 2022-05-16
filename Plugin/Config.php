<?php

namespace Ced\Betterthat\Plugin;

/**
 * Directory separator shorthand
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfigManager;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    public $scopeConfigResource;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    public $directoryList;

    /**
     * @var \Ced\Betterthat\Helper\Config
     */
    public $config;

    public $cache;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigManager,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\Config\Model\ResourceModel\Config $config,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Ced\Betterthat\Helper\Config $BetterthatConfig
    ) {
        $this->scopeConfigManager = $scopeConfigManager;
        $this->scopeConfigResource = $config;
        $this->messageManager = $messageManager;
        $this->directoryList = $directoryList;
        $this->cache = $cache;
        $this->config = $BetterthatConfig;
    }

    public function afterSave(
        \Magento\Config\Model\Config $subject
    ) {
        $configPost = $subject->getData();
        if (isset($configPost['section']) and $configPost['section'] == 'betterthat_config') {
            $enabled = $this->config->isEnabled();
            if ($enabled) {
                $response = $this->config->validate();
                if ($response['status']) {
                    $this->messageManager->addSuccessMessage('BetterThat credentials are valid.');
                    $this->scopeConfigResource->saveConfig(
                        'betterthat_config/betterthat_setting/valid',
                        '1',
                        'default',
                        0
                    );
                } else {
                    $this->messageManager->addErrorMessage(isset($response['message']) ? 'BetterThat: '.$response['message'] : '');
                    $this->scopeConfigResource->saveConfig(
                        'betterthat_config/betterthat_setting/valid',
                        '0',
                        'default',
                        0
                    );
                }

                // Cleaning cache
                $cacheType = [
                    'config',
                ];
                foreach ($cacheType as $cache) {
                    $this->cache->cleanType($cache);
                }
            }
        }
    }
}
