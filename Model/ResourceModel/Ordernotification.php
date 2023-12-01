<?php

/**
 * Yudiz
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Yudiz
 * @package     Yudiz_Ordernotification
 * @copyright   Copyright (c) 2023 Yudiz (https://www.Yudiz.com/)
 */

namespace Yudiz\Ordernotification\Model\ResourceModel;

class Ordernotification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(
            'yudiz_ordernotification',
            'ordernotification_id'
        );
    }
}
