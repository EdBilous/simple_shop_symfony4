<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.06.18
 * Time: 16:02
 */

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use App\Services\ImagesUploaderService;

class ProductManagerService
{
    /**
     * save EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $imagesUploader;

    public function __construct(EntityManager $entityManager, ImagesUploaderService $imagesUploader)
    {
        $this->em = $entityManager;
        $this->imagesUploader = $imagesUploader;
    }

    public function productCreate($files, Product $product)
    {
        if ($files) {
            foreach ($files as $file) {
                $fileName = $this->imagesUploader->upload($file->getFile());
                $file->setSrc($fileName);
                $product->addImage($file);
            }
            $this->em->persist($product);
            $this->em->flush();
        }
        return;
    }

    public function removeProduct(Product $product)
    {
        if ($product->getImages()){
            foreach ($product->getImages() as $image){
                $product->removeImage($image);
                $this->imagesUploader->deleteImages($image);
            }
        }

        if ($product->getReviews()){
            foreach ($product->getReviews() as $review){
                $product->removeReview($review);
            }
        }
//        removeImage
//        removeReview
        $this->em->remove($product);
        $this->em->flush();

    }
}