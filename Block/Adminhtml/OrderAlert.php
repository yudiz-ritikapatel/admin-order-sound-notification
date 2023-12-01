<?php

namespace Yudiz\Ordernotification\Block\Adminhtml;

class OrderAlert extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Yudiz\Ordernotification\Helper\Data
     */
    protected $dataHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Yudiz\Ordernotification\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Yudiz\Ordernotification\Helper\Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
        parent::__construct($context);
    }

    /**
     * Check if the module is enabled
     *
     * @return string|bool
     */
    public function getEnable()
    {
        if (!$this->dataHelper->moduleEnabled()) {
            return '';
        }

        return true;
    }

    /**
     * Get the audio file
     *
     * @return string
     */
    public function getSoundFile()
    {
        return $this->dataHelper->getAudioFile();
    }

    /**
     * Get the sound type
     *
     * @return string
     */
    public function getSoundType()
    {
        return $this->dataHelper->getSoundType();
    }

    /**
     * Get delay time
     *
     * @return string
     */
    public function getDealy()
    {
        return $this->dataHelper->getDealy();
    }

    /**
     * Get media url
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . 'order_sound/';
    }
}
