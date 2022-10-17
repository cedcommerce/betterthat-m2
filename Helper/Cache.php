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

namespace Betterthat\Betterthat\Helper;

class Cache extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Cache key identifier
     */
    public const CACHE_KEY_IDENTIFIER = 'ced_Betterthat_';
    public const PROFILE_CACHE_KEY = 'profile_';
    public const PROFILE_PRODUCT_CACHE_KEY = 'profile_product_';
    public const CRON_STATUS_KEY = 'cron_status';

    /**
     * @var \Magento\Framework\App\Cache
     */
    public $cache;

    /**
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->cache = $cache;
        parent::__construct($context);
    }

    /**
     * GetValue
     *
     * @param  string $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        $cacheKey = self::CACHE_KEY_IDENTIFIER . $key;
        $value = $this->cache->load($cacheKey);
        return $value === false ? null : json_decode($value, true);
    }

    /**
     * SetValue
     *
     * @param string $key
     * @param string $value
     * @param array $tags
     * @param string $lifeTime
     * @return void
     */
    public function setValue($key, $value, array $tags = [], $lifeTime = null)
    {
        if ($value === null) {
            $value = [];
        }

        if ($lifeTime === null || (int)$lifeTime <= 0) {
            $lifeTime = 60 * 60 * 24 * 365 * 5;
        }

        $cacheKey = self::CACHE_KEY_IDENTIFIER . $key;

        $preparedTags = [self::CACHE_KEY_IDENTIFIER . '_main'];
        foreach ($tags as $tag) {
            $preparedTags[] = self::CACHE_KEY_IDENTIFIER . '_' . $tag;
        }

        $this->cache->save(json_encode($value), $cacheKey, $preparedTags, (int)$lifeTime);
    }

    /**
     * RemoveValue
     *
     * @param string $key
     * @return void
     */
    public function removeValue($key)
    {
        $cacheKey = self::CACHE_KEY_IDENTIFIER . $key;
        $this->cache->remove($cacheKey);
    }

    /**
     * RemoveAllValues
     *
     * @return void
     */
    public function removeAllValues()
    {
        $this->removeTagValues('main');
    }

    /**
     * RemoveTagValues
     *
     * @param string $tag
     * @return void
     */
    public function removeTagValues($tag)
    {
        $tags = [self::CACHE_KEY_IDENTIFIER . '_' . $tag];
        $this->cache->clean($tags);
    }
}
