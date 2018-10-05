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
     * @Groups({"category_list", "category_details"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"category_list", "category_details"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @Groups({"category_details"})
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="category", cascade={"persist"})
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @param CreateCategoryRequest $dto
     *
     * @return Category
     */
    public static function createFromDTO(CreateCategoryRequest $dto): Category
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

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

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }
}
