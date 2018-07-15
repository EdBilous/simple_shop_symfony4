<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Category")
     */
    private $parent;

    public function __construct() {
        //  $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString() {
        return $this->getName();
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set title
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get title
     *
     * @return string
     */
    public function getname()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }
    /**
     * Get parent
     *
     */
    public function getParent()
    {
        return $this->parent;
    }

}
