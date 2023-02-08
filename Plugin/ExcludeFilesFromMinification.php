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
namespace Betterthat\Betterthat\Plugin;

class ExcludeFilesFromMinification
{
    /**
     * AroundGetExcludes
     *
     * @param \Magento\Framework\View\Asset\Minification $subject
     * @param callable $proceed
     * @param string $contentType
     * @return mixed
     */
    public function aroundGetExcludes(
        \Magento\Framework\View\Asset\Minification $subject,
        callable $proceed,
        $contentType
    ) {
        $result = $proceed($contentType);
        if ($contentType != 'js') {
            return $result;
        }
        $result[] = 'Betterthat_Betterthat/js/vkbeautify.0.99.00.beta';
        return $result;
    }
}
