<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.07.18
 * Time: 0:03
 */

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserManagerService
{
    /**
     * save EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function addNewUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();

    }
}