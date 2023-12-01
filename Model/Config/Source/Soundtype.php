<?php
namespace Yudiz\Ordernotification\Model\Config\Source;

class Soundtype implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get options for sound type.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'sound', 'label' => 'Play sound'],
            ['value' => 'speech', 'label' => 'Play speech'],
        ];
    }
}
