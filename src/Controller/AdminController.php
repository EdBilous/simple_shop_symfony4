<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Orders;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\User;
use App\Form\BrandType;
use App\Form\CategoryType;
use App\Form\OrdersType;
use App\Form\ProductType;
use App\Form\UserType;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Services\OrderManagerService;
use App\Services\ProductManagerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/products-list", name="admin_products")
     */
    public function productsList(ProductRepository $productRepository)
    {
        return $this->render('admin/pages/products_list.html.twig', [
            'products' => $productRepository->findAllProducts()]);
    }

    /**
     * @Route("/product/new", name="admin_product_new")
     * @Method({"GET", "POST"})
     */
    public function newProduct
    (
        Request $request,
        ProductManagerService $productManagerService
    )
    {
        $product = new Product();
        $product->addImage(new ProductImage());

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files= $form->getData()->getImages();

            $productManagerService->productCreate($files, $product);

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/pages/product_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/product/{slug}/edit", name="admin_product_edit", methods="GET|POST")
     */
    public function editProduct(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/pages/product_edit.html.twig', [
            'product' => $product,
            'edit_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users-list", name="admin_users", methods="GET")
     */
    public function usersList(UserRepository $userRepository)
    {
        return $this->render('admin/pages/users_list.twig', [
            'users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/user/{id}/edit", name="admin_user_edit", methods="GET|POST")
     */
    public function editUser(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/pages/user_edit.html.twig', [
            'user' => $user,
            'edit_form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/new", name="admin_category_new", methods="GET|POST")
     */
    public function categoryNew(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/pages/category_new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/brand/new", name="admin_brand_new", methods="GET|POST")
     */
    public function brandNew(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/pages/brand_new.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/del/{id}", name="admin_product_delete", methods="DELETE")
     */
    public function deleteProduct(ProductManagerService $productManager,
                                 Request $request,
                                 Product $product)
    {
//        dump($product); die()
        if ($this->isCsrfTokenValid('delete'.$product->getId(),
            $request->request->get('_token'))) {
            $productManager->removeProduct($product);
        }

        return $this->redirectToRoute('admin_products');
    }

    /**
     * @Route("/orders", name="admin_orders_list")
     */
    public function ordersList(OrdersRepository $ordersRepository)
    {

        return $this->render('admin/pages/orders/orders_list.html.twig', [
            'orders' => $ordersRepository->findAllOrders()]);
    }

    /**
     * @Route("/orders/{id}", name="admin_orders_show", methods="GET")
     */
    public function orderShow(OrderManagerService $orderManager, Orders $orders)
    {

       $products = $orderManager->getProductsByOrders($orders);
        return $this->render('admin/pages/orders/view_details.html.twig', [
            'order' => $orders,
            'products' => $products ]);
    }

    /**
     * @Route("/orders/{id}/edit", name="orders_edit", methods="GET|POST")
     */
    public function editOrder(Request $request, Orders $order)
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_edit', [
                'id' => $order->getId()]);
        }

        return $this->render('admin/pages/orders/order_edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/status_change", name="orders_change_status", methods="POST")
     */
    public function orderChangeStatus(Request $request, Orders $order, OrderManagerService $orderManager)
    {
        if ($this->isCsrfTokenValid('change'.$order->getId(), $request->request->get('_token'))) {
            $status = $request->request->get('status');
            $orderId = $request->request->get('orderId');

                $orderManager->changeOrderStatus($orderId, $status);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/orders/{id}/del", name="orders_delete", methods="DELETE")
     */
    public function orderDelete(Request $request, Orders $order, OrderManagerService $orderManager)
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderManager->deleteOrder($order);
        }

        return $this->redirect($request->headers->get('referer'));
    }
}