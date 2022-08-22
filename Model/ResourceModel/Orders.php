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
namespace Betterthat\Betterthat\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Orders extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        //Betterthat_orders is table and id is primary key of this table
        $this->_init('betterthat_orders', 'id');
    }
}
