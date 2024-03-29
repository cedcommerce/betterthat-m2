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

namespace Ced\Betterthat\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class OrderFailed
 *
 * @package Ced\Betterthat\Model
 */
class OrderFailed extends AbstractModel
{
    public function _construct()
    {
        $this->_init('Ced\Betterthat\Model\ResourceModel\OrderFailed');
    }
}
