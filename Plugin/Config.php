<?php

namespace Betterthat\Betterthat\Plugin;

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
     * @var \Betterthat\Betterthat\Helper\Config
     */
    public $config;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    public $cache;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigManager
     * @param \Magento\Framework\App\Cache\TypeListInterface $cache
     * @param \Magento\Config\Model\ResourceModel\Config $config
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Betterthat\Betterthat\Helper\Config $BetterthatConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigManager,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\Config\Model\ResourceModel\Config $config,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Betterthat\Betterthat\Helper\Config $BetterthatConfig
    ) {
        $this->scopeConfigManager = $scopeConfigManager;
        $this->scopeConfigResource = $config;
        $this->messageManager = $messageManager;
        $this->directoryList = $directoryList;
        $this->cache = $cache;
        $this->config = $BetterthatConfig;
    }

    /**
     * AfterSave
     *
     * @param \Magento\Config\Model\Config $subject
     * @return void
     */
    public function afterSave(
        \Magento\Config\Model\Config $subject
    ) {
        $configPost = $subject->getData();
        if (isset($configPost['section']) && $configPost['section'] == 'betterthat_config') {
            //disabled due to test category not available
            $enabled = $this->config->isEnabled();
            if ($enabled) {
                $response = $this->config->validate(); // commented due to API timeout.
                if (isset($response['status']) && $response['status']) {
                    $this->messageManager->addSuccessMessage('BetterThat credentials are valid.');
                    $this->scopeConfigResource->saveConfig(
                        'betterthat_config/betterthat_setting/valid',
                        '1',
                        'default',
                        0
                    );
                } elseif (isset($response['message'])) {
                    if ($response['message'] == 'Invalid client id.'
                        || $response['message'] == 'Header is missing.'
                        || $response['message'] =='Domain is not secure.') {
                        $this->scopeConfigResource->saveConfig(
                            'betterthat_config/betterthat_setting/client_id',
                            '',
                            'default',
                            0
                        );
                    }

                    if ($response['message'] == 'Invalid client secret key.'
                        || $response['message'] == 'Header is missing.'
                        || $response['message'] =='Domain is not secure.') {
                        $this->scopeConfigResource->saveConfig(
                            'betterthat_config/betterthat_setting/client_secret',
                            '',
                            'default',
                            0
                        );
                    }

                    if ($response['message'] == "This domain is not allowed with this key."
                        || $response['message'] =='Domain is not secure.'
                        || $response['message'] == 'Header is missing.') {
                        $this->scopeConfigResource->saveConfig(
                            'betterthat_config/betterthat_setting/client_domain',
                            '',
                            'default',
                            0
                        );
                    }

                    $this->messageManager
                        ->addErrorMessage(
                            isset($response['message'])
                            ? 'BetterThat: '.$response['message'] : 'BetterThat credentials are invalid.'
                        );

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
