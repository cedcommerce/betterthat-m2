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

namespace Ced\BetterThat\Model\Source\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class ServiceUrl
 * @package Ced\Betterthat\Model\Source
 */
class SandboxEndpoints extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        $serviceUrls = [];

        $serviceUrls[] = [
            'value' => \CatchSdk\Core\Request::CATCH_SANDBOX_API_URL,
            'label' => __('Sandbox API URL'),
        ];

        return $serviceUrls;
    }
}
