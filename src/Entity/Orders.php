<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.07.18
 * Time: 22:54
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 * @ORM\Table
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * Status of card. 0: discard. 1: active. 2: appruved. 3: complete.
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Reference to user of this Card
     * @ORM\OneToOne(targetEntity="User", inversedBy="order")
     */

    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOrder", mappedBy="orders")
     */
    private $productOrders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->status = 'active';
        $this->productOrders = new ArrayCollection();
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
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    public function __toString() {
        return "Заказ для " . $this->getUserName();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection|ProductOrder[]
     */
    public function getProductOrders(): Collection
    {
        return $this->productOrders;
    }

    public function addProductOrder(ProductOrder $productOrder): self
    {
        if (!$this->productOrders->contains($productOrder)) {
            $this->productOrders[] = $productOrder;
            $productOrder->setOrders($this);
        }

        return $this;
    }

    public function removeProductOrder(ProductOrder $productOrder): self
    {
        if ($this->productOrders->contains($productOrder)) {
            $this->productOrders->removeElement($productOrder);
            // set the owning side to null (unless already changed)
            if ($productOrder->getOrders() === $this) {
                $productOrder->setOrders(null);
            }
        }

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}