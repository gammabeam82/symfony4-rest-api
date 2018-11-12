<?php

namespace App\Entity;

use App\Request\Category\CreateCategoryRequest;
use App\Request\Category\UpdateCategoryRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @Groups({"category_list", "category_details"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"category_list", "category_details"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var Post[]
     *
     * @Groups({"category_details", "category_posts"})
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="category", cascade={"persist"})
     */
    private $posts;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @param CreateCategoryRequest $dto
     *
     * @return Category
     */
    public static function createFromDTO(CreateCategoryRequest $dto): self
    {
        $category = new Category();
        $category->setName($dto->name);

        return $category;
    }

    /**
     * @param UpdateCategoryRequest $dto
     */
    public function updateFromDTO(UpdateCategoryRequest $dto): void
    {
        $this->setName($dto->name);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     *
     * @return Category
     */
    public function addPost(Post $post): self
    {
        if (false === $this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return Category
     */
    public function removePost(Post $post): self
    {
        if (false !== $this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
