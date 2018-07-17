<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends BaseFixture
{
    private static $productName = [
        'Wilson shirt',
        'Mai jacket',
        'Ciaran t-shirt',
    ];

    private static $productDescription = [
        'Our most popular bottle, available in a variety of colors to help brighten up anybody’s gear. The large opening on our wide-mouth bottles easily accommodates ice cubes, fits most water purifiers and filters, and makes hand washing a breeze. The attached loop-top never gets lost and screws on and off easily. Printed graduations let keep track of your hydration',
        'Holy Outboard Motor, Batman! The Batboat is another in our line of newly reissued Batman vehicles from Polar Lights! Full of fine details based on the original design from the 1960’s including Batman and Robin figures and full color decals.',
        'Make a statement with our rustic black hooped American oak barrels. Even though smaller than their larger cousins, our specialty oak barrels are the real thing and will be right at home in your kitchen or bar. The black steel hoops will react to the changes in humidity and seasons and will age to a beautiful patina giving your barrel that special vintage look.',
    ];

    private static $productPrice = [
        '29',
        '39',
        '49',
    ];

    private static $productImage = [
        '00.jpg',
        '39.jpg',
        '49.jpg',
    ];


    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Product::class, 5, function(Product $product, $count) {
            $product->setPrice($this->faker->randomElement(self::$productPrice));
            $product->setName($this->faker->randomElement(self::$productName));
            $product->setDescription($this->faker->randomElement(self::$productDescription));
            $product->addImage($this->faker->randomElement(self::$productImage));
        });

        $manager->flush();

    }
}