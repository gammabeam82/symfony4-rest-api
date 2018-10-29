<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as Faker;

class PostFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();

            $post
                ->setTitle($this->faker->words(3, true))
                ->setArticle($this->faker->paragraphs(5, true))
                ->setCreatedAt(new \DateTime(sprintf("-%d days", $i + 2)));

            /** @var \App\Entity\Category $category */
            $category = $this->getReference(
                sprintf("%s%d", CategoryFixtures::CATEGORY_REFERENCE, random_int(1, 4))
            );
            $post->setCategory($category);

            for ($t = 1; $t < 3; $t++) {
                /** @var \App\Entity\Tag $tag */
                $tag = $this->getReference(
                    sprintf("%s%d", TagFixtures::TAG_REFERENCE, $t + random_int(0, 2))
                );
                $post->addTag($tag);
            }

            /** @var \App\Entity\User $user */
            $user = $this->getReference(UserFixtures::USER_REFERENCE);
            $post->setUser($user);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            TagFixtures::class
        ];
    }
}
