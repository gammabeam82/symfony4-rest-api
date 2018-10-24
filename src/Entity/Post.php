<?php

namespace App\Entity;

use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @Groups({"post_list", "post_details", "category_details"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"post_list", "post_details", "category_details"})
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Groups({"post_details"})
     * @ORM\Column(type="text")
     */
    private $article;

    /**
     * @Groups({"post_list", "post_details"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups({"post_list", "post_details"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     */
    private $category;

    /**
     * @Groups({"post_list", "post_details"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @param CreatePostRequest $dto
     *
     * @return Post
     */
    public static function createFromDTO(CreatePostRequest $dto): Post
    {
        $post = new Post();

        $post
            ->setTitle($dto->title)
            ->setCreatedAt($dto->createdAt)
            ->setArticle($dto->article)
            ->setCategory($dto->category);

        return $post;
    }

    /**
     * @param UpdatePostRequest $dto
     *
     * @return Post
     */
    public function updateFromDTO(UpdatePostRequest $dto): Post
    {
        $this
            ->setTitle($dto->title)
            ->setArticle($dto->article)
            ->setCategory($dto->category);

        return $this;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getArticle(): ?string
    {
        return $this->article;
    }

    /**
     * @param string $article
     *
     * @return Post
     */
    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return Post
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
