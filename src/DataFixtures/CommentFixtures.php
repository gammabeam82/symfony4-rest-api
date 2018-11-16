<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var Faker
     */
    protected $faker;

    /**
     * PostFixtures constructor.
     *
     * @param Faker $faker
     *
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        /** @var \App\Entity\Post $post */
        $post = $this->getReference(PostFixtures::POST_REFERENCE);

        for ($i = 0; $i < 5; $i++) {
            $comment = new Comment();

            $comment
                ->setCreatedAt(new \DateTime('now'))
                ->setUpdatedAt(new \DateTime('now'))
                ->setMessage($this->faker->paragraphs(2, true))
                ->setUser($user)
                ->setPost($post);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
            UserFixtures::class
        ];
    }
}
