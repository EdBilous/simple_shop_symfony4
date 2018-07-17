<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Product;
use App\Form\CartType;
use App\Form\OrdersType;
use App\Repository\ProductRepository;
use App\Services\CartManagerService;
use App\Services\OrderManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository)
    {

        return $this->render('default/pages/main.html.twig', [
            'products' => $productRepository->findNewProducts(4)
        ]);
    }

    /**
     * @Route("products", name="products_route")
     */
    public function products(ProductRepository $productRepository)
    {


        return $this->render('default/pages/products.html.twig', [
            'products' => $productRepository->findAllProducts()]);
    }

    /**
     * @Route("show/{slug}", name="show_route")
     */
    public function show(Product $product, Request $request, CartManagerService $cartManager)
    {
        if (!$product) {
            throw $this->createNotFoundException('Unable to find Product.');
        }

        $productId = ['productId' => $product->getId()];
        $form = $this->createForm(CartType::class, $productId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->getData()['quantity'];
            $cartManager->addProduct($product, $quantity);

            $this->addFlash(
                'notice',
                "« $quantity » item(s) added to shopping cart");
        }

        return $this->render('default/pages/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()]);

    }

    /**
     * @Route("contact", name="contact_route")
     */
    public function contact()
    {
        return $this->render('default/pages/contact.html.twig');
    }

    /**
     * @Route("checkout", name="checkout_route")
     */
    public function checkout(Request $request,
                             OrderManagerService $orderManager,
                             CartManagerService $cartManager)
    {
        $orders = new Orders();
        $form = $this->createForm(OrdersType::class, $orders);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dump($form);die();
            $orders->setTotalPrice($request->request->get('totalPrice'));

            $orderManager->addProductOrder($orders);
                $this->addFlash(
                    'notice',
                    'order is accepted!');

                return $this->redirectToRoute('checkout_delete_all');
        }
        return $this->render('default/pages/checkout.html.twig', [
            'cart' => $cartManager->getCart(),
            'form' => $form->createView()]);
    }

    /**
     * @Route("/checkout/delete/{product}", name="checkout_delate_product")
     */
    public function deleteProduct(Request $request, Product $product, CartManagerService $cartManager)
    {
        if($product){
            $cartManager->deleteProductFromCart($product);
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/checkout/delete-all/", name="checkout_delete_all")
     */
    public function clearCart(Request $request, CartManagerService $cartManager)
    {
        $cartManager->clearCart();
        return $this->redirect($request->headers->get('referer'));
    }

}
