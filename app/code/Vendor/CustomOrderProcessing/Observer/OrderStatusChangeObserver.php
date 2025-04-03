<?php
namespace Vendor\CustomOrderProcessing\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class OrderStatusChangeObserver implements ObserverInterface
{
    protected $logger;
    protected $transportBuilder;
    protected $storeManager;

    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
    }

    public function execute(Observer $observer )
    {
        $order = $observer->getEvent()->getOrder();
        $oldStatus = $order->getOrigData('status');
        $newStatus = $order->getStatus();
        $orderId = $order->getIncrementId();
        $timestamp = date('Y-m-d H:i:s');

        // Log the status change
        $this->logger->info("Order ID: $orderId, Old Status: $oldStatus, New Status: $newStatus, Timestamp: $timestamp");

        // If the order is marked as shipped, send an email notification
        if ($newStatus === 'shipped') {
            $this->sendEmailNotification($order);
        }
    }

    protected function sendEmailNotification($order)
    {
        $store = $this->storeManager->getStore();
        $templateVars = ['order' => $order];
        $from = ['name' => 'Your Store', 'email' => 'store@example.com'];
        $to = $order->getCustomerEmail();

        $transport = $this->transportBuilder
            ->setTemplateIdentifier('order_shipped_email_template')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store->getId()])
            ->setTemplateVars($templateVars)
            ->setFromByScope($from)
            ->addTo($to)
            ->getTransport();

        $transport->sendMessage();
    }
}<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Event/etc/events.xsd">
    <event name="sales_order_save_after">
        <observer name="custom_order_status_change_observer" instance="Vendor\CustomOrderProcessing\Observer\OrderStatusChangeObserver" />
    </event>
</config>