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
     * @var\Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $lastOrderFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification\CollectionFactory $collectionFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $lastOrderFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Yudiz\Ordernotification\Model\ResourceModel\Ordernotification\CollectionFactory $collectionFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $lastOrderFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
        $this->lastOrderFactory = $lastOrderFactory;
    }

    /**
     * Execute the action.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $data = [
            'success' => 200,
            'message' => __("Play sound"),
        ];

        $lastOrder = $this->lastOrderFactory->create();
        $lastOrder->getSelect()->order('entity_id DESC')->limit(1);
        $lastOrder = $lastOrder->getFirstItem();
        $orderId = $lastOrder->getId(); // Last order ID

        $collection = $this->collectionFactory->create(); // order notification custom
        $orderNotificationFirstItem = $collection->getFirstItem();

        $text = "";
        $text = "New order " . $lastOrder->getIncrementId() . " placed by ";
        $text .= $lastOrder->getCustomerFirstname() . " " . $lastOrder->getCustomerLastname();
        $data['message'] = $text;

        if ($orderNotificationFirstItem) {
            if ($orderNotificationFirstItem->getOrderId() == $orderId) {
                $data['success'] = 413;
                $data['message'] = __("Not Play sound");
            } else {
                if ($lastOrder->getStatus() == 'payment_review' ||
                    $lastOrder->getStatus() == 'pending_payment'
                ) {
                    $data['success'] = 413;
                    $data['message'] = __("Not Play sound, payment remain");
                } else {
                    $orderNotificationFirstItem->setOrderId($orderId);
                    $orderNotificationFirstItem->setCreatedTime(date('Y-m-d H:i:s'));
                    $orderNotificationFirstItem->save();
                    $data['success'] = 200;
                    $data['message'] = $text;
   
                    //show log
                    // $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/ordernotification.log');
                    // $logger = new \Zend_Log();
                    // $logger->addWriter($writer);
                    // $logger->info(json_encode(["data" => $data, 'orderId' => $orderId]));
                }
            }
        }
        //Send response to Ajax query
        $result = $this->resultJsonFactory->create();
        return $result->setData($data);
    }
}
