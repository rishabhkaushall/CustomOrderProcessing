<?php
namespace Vendor\CustomOrderProcessing\Api;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class OrderStatusUpdate implements OrderStatusUpdateInterface
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function updateStatus($incrementId, $newStatus)
    {
        try {
            $order = $this->orderRepository->get($incrementId);
            $order->setStatus($newStatus);
            $this->orderRepository->save($order);
            return __('Order status updated successfully.');
        } catch (NoSuchEntityException $e) {
            throw new LocalizedException(__('Order not found.'));
        } catch (\Exception $e) {
            throw new LocalizedException(__('Error updating order status.'));
        }
    }
}