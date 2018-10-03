<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as Faker;

class PostFixtures extends Fixture
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

            $manager->persist($post);
        }

        $manager->flush();
    }
}