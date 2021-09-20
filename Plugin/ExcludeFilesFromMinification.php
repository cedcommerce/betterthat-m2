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
namespace Ced\Betterthat\Plugin;

class ExcludeFilesFromMinification
{
    public function aroundGetExcludes(
        \Magento\Framework\View\Asset\Minification $subject,
        callable $proceed,
        $contentType
    ) {
        $result = $proceed($contentType);
        if ($contentType != 'js') {
            return $result;
        }
        $result[] = 'Ced_Betterthat/js/vkbeautify.0.99.00.beta';
        return $result;
    }
}
