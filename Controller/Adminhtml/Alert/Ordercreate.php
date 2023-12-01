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

namespace Yudiz\Ordernotification\Controller\Adminhtml\Alert;

use Magento\Backend\App\Action;

class Ordercreate extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification\CollectionFactory $collectionFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification\CollectionFactory $collectionFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Execute the action.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/ordernotification.log');
        //             $logger = new \Zend_Log();
        //             $logger->addWriter($writer);
        //             $logger->info();
        //             // die("ff");

        $data = [
            'success' => 200,
            'message' => __("Play sound"),
        ];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderDatamodel = $objectManager->get(\Magento\Sales\Model\Order::class)->getCollection()->getLastItem();
        $orderId = $orderDatamodel->getId(); //orders Id

        $collection = $this->collectionFactory->create(); // order notification custom
        $orderNotificationFirstItem = $collection->getFirstItem();

        $text = "";
        $text = "New order " . $orderDatamodel->getIncrementId() . " placed by ";
        $text .= $orderDatamodel->getCustomerFirstname() . " " . $orderDatamodel->getCustomerLastname();
        $data['message'] = $text;

        if ($orderNotificationFirstItem) {
            if ($orderNotificationFirstItem->getOrderId() == $orderId) {
                $data['success'] = 413;
                $data['message'] = __("Not Play sound");
            } else {
                if ($orderDatamodel->getStatus() == 'payment_review' ||
                    $orderDatamodel->getStatus() == 'pending_payment'
                ) {
                    $data['success'] = 413;
                    $data['message'] = __("Not Play sound, payment remain");
                } else {
                    $orderNotificationFirstItem->setOrderId($orderId);
                    $orderNotificationFirstItem->setCreatedTime(date('Y-m-d H:i:s'));
                    $orderNotificationFirstItem->save();
                    $data['success'] = 200;
                    $data['message'] = $text;
                    // $logger->info(json_encode(["data" => $data, 'orderId' => $orderId]));
                }
            }
        }
        //Send response to Ajax query
        //$data = $orderId;
        $result = $this->resultJsonFactory->create();
        return $result->setData($data);
    }
}
