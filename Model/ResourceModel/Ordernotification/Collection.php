<?php

namespace Yudiz\Ordernotification\Model\ResourceModel\Ordernotification;

use Yudiz\Ordernotification\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'ordernotification_id';

    /**
     * @var mixed
     */
    protected $_previewFlag;

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(
            \Yudiz\Ordernotification\Model\Ordernotification::class,
            \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification::class
        );

        $this->_map['fields']['ordernotification_id'] = 'main_table.ordernotification_id';
    }
}
