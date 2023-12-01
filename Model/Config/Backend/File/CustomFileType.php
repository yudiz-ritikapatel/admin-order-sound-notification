<?php
namespace Yudiz\Ordernotification\Model\Config\Backend\File;

class CustomFileType extends \Magento\Config\Model\Config\Backend\File
{
    /**
     * Get allowed extensions.
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return ['mp3', 'mp4', 'wav', 'wma', 'aac'];
    }

    /**
     * Get allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['mp3', 'mp4', 'wav', 'wma', 'aac'];
    }
}
