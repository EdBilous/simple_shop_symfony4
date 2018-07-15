<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.18
 * Time: 23:00
 */

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\ProductImage;


class ImagesUploaderService
{
    /**
     * @var
     */
    private $targetDir;

    private $em;
    /**
     * ImagesUploaderService constructor.
     * @param $targetDir
     */
    public function __construct(EntityManagerInterface $em, $targetDir)
    {
        $this->em = $em;
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);
//dump($fileName); die();
        return $fileName;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function deleteImages(ProductImage $image)
    {
        if ($image){
        $filesystem = new Filesystem();
        $filePath = "$this->targetDir" . "/" . "$image";
        $filesystem->remove($filePath);
        $this->em->remove($image);
        }
        return;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}