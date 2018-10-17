<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as Faker;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category';

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
        for ($i = 1; $i < 5; $i++) {
            $category = new Category();
            $category->setName($this->faker->unique()->words(1, true));

            $manager->persist($category);

            $this->addReference(sprintf("%s%d", self::CATEGORY_REFERENCE, $i), $category);
        }

        $manager->flush();
    }
}
