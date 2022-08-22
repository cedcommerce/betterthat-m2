<?php

/**
 * Betterthat
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://betterthat.com/license-agreement.txt
 *
 * @category  Betterthat
 * @package   Betterthat_Betterthat
 * @author    Betterthat Core Team <connect@betterthat.com>
 * @copyright Copyright BETTERTHAT(https://betterthat.com/)
 * @license   https://betterthat.com/license-agreement.txt
 */

namespace Betterthat\BetterThat\Model\Source\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class ServiceUrl
 *
 * @package Betterthat\Betterthat\Model\Source
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
