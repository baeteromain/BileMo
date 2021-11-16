<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $hasher;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security, UserPasswordHasherInterface $hasher)
    {
        $this->security = $security;
        $this->hasher = $hasher;
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        $data->setPassword($this->hasher->hashPassword($data, $data->getPassword()));
        $data->setCustomer($this->security->getUser());
        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}