<?php

namespace App\Services;

use App\Entity\Orders;
use App\Entity\ProductOrder;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderManagerService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProductRepository
     */

    private $productRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var OrdersRepository
     */
    private $op;

    public function __construct(SessionInterface $session,
                                ProductRepository $productRepository,
                                EntityManagerInterface $em,
                                OrdersRepository $ordersRepository
    )
    {
        $this->op = $ordersRepository;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    /**
     * @param $orders
     * @return bool
     */
    public function addProductOrder($orders)
    {
        $this->em->persist($orders);

        $cart = $this->session->get('cart');
        if ($cart) {
            foreach ($cart as $productId => $quantity) {
                $price = $this->productRepository->find($productId)->getPrice();

                $productOrder = new ProductOrder();
                $productOrder->setPrice($price);
                $productOrder->setProductId($productId);
                $productOrder->setQuantity($quantity);
                $productOrder->setOrders($orders);
                $this->em->persist($productOrder);
            }
            $this->em->flush();
        }
    }

    /**
     * @param Orders $order
     * @return array
     */
    public function getProductsByOrders(Orders $orders)
    {
        $products = [];

        if ($orders) {

            $order = $orders->getProductOrders();
            foreach ($order as $item) {
                $arr['price'] = $item->getPrice();
                $arr['quantity'] = $item->getQuantity();
                $arr['product'] = $this->productRepository->find($item->getProductId());
                $products[] = $arr;
            }

            return $products;
        }
    }

    public function getUserByOrders(Orders $orders)
    {
    }

    public function deleteOrder(Orders $orders)
    {

    }
}