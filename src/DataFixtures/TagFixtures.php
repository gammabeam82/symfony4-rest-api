<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as Faker;

class TagFixtures extends Fixture
{
    public const TAG_REFERENCE = 'tag';

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
            $tag = new Tag();
            $tag->setName($this->faker->unique()->words(1, true));

            $manager->persist($tag);

            $this->addReference(sprintf("%s%d", self::TAG_REFERENCE, $i), $tag);
        }

        $manager->flush();
    }
}
