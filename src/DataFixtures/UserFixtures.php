<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * UserFixtures constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user
            ->setUsername('testuser')
            ->setEmail('qwerty@asdfg.zx')
            ->setPlainPassword('p@ssword')
            ->setEnabled(true)
            ->setUpdatedAt(new \DateTime('now'))
            ->setCreatedAt(new \DateTime('now'))
            ->addRole('ROLE_ADMIN');

        $this->userManager->updateUser($user);

        $this->addReference(self::USER_REFERENCE, $user);
    }
}
