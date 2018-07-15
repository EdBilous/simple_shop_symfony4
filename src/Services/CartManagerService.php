<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.07.18
 * Time: 21:29
 */

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

class CartManagerService
{
    private $session;
    private $repository;
    private $sessionName;

    /**
     * @param SessionInterface $session
     * @param ProductRepository $repository
     */
    public function __construct(SessionInterface $session, ProductRepository $repository)
    {
        $this->sessionName= 'cart';
        $this->session = $session;
        $this->repository = $repository;
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function addProduct(Product $product, $quantity)
    {
        $cart = $this->session->get($this->sessionName);

        if ($cart != null && isset($cart[$product->getId()])) {
            $cart[$product->getId()] += $quantity;
            $this->session->set($this->sessionName, $cart);
        }else{
            $cart[$product->getId()] = $quantity;
            $this->session->set($this->sessionName, $cart);
        }
    }

    /**
     * @return array
     */
    public function getCart()
    {
        $array = [];
//        $orderPrice= null;
        if ($this->session->has($this->sessionName)) {

            foreach ($this->session->get($this->sessionName) as $productId => $quantity) {
                $arr['quantity'] = $quantity;
                $arr['product'] = $this->repository->find($productId);
                $arr['totalPrice']= $arr['quantity'] * $arr['product']->getPrice();
//                $orderPrice += $arr['totalPrice'];
                $array[] = $arr;
            }
        }

        return $array;
    }

    /**
     * @param Product $product
     */
    public function deleteProductFromCart(Product $product)
    {
        $cart = $this->session->get($this->sessionName);

        if ($this->session->has($this->sessionName) && isset($cart[$product->getId()])) {
            unset($cart[$product->getId()]);
            $this->session->set($this->sessionName, $cart);
        }
    }


    public function clearCart()
    {
        if ($this->session->has($this->sessionName)) {

            $this->session->remove($this->sessionName);

        }
    }
}