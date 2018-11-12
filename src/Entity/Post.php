<?php

namespace App\Entity;

use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @Groups({
     *     "post_list",
     *     "post_details",
     *     "category_list",
     *     "category_details",
     *     "tag_list",
     *     "tag_details",
     *     "category_posts",
     *     "tag_posts",
     *     "user_posts"
     * })
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({
     *     "post_list",
     *     "post_details",
     *     "category_details",
     *     "tag_details",
     *     "category_posts",
     *     "tag_posts",
     *     "user_posts"
     * })
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Groups({"post_details"})
     * @ORM\Column(type="text")
     */
    private $article;

    /**
     * @var \DateTimeInterface
     *
     * @Groups({"post_list", "post_details"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Category
     *
     * @Groups({"post_list", "post_details"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     */
    private $category;

    /**
     * @var User
     *
     * @Groups({"post_list", "post_details"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Tag[]
     *
     * @Groups({"post_list", "post_details"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="posts_tags",
     *     joinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *  }
     * )
     */
    private $tags;

    /**
     * @var \DateTimeInterface
     *
     * @Groups({"post_details"})
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     * @Groups({"post_list", "post_details"})
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @var PostImage[]
     *
     * @Groups({"post_list", "post_details"})
     * @ORM\OneToMany(targetEntity="App\Entity\PostImage", mappedBy="post", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $images;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

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
            ->setSummary($dto->summary)
            ->setCreatedAt($dto->createdAt)
            ->setUpdatedAt($dto->updatedAt)
            ->setArticle($dto->article)
            ->setCategory($dto->category)
            ->setTags($dto->tags)
            ->setImages($dto->images);

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
            ->setSummary($dto->summary)
            ->setArticle($dto->article)
            ->setCategory($dto->category)
            ->setUpdatedAt($dto->updatedAt)
            ->setTags($dto->tags);

        foreach ($dto->getFiles() as $image) {
            /** @var PostImage $image */
            $image->setPost($this);
            $this->addImage($image);
        }

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

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     *
     * @return Post
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return Post
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection|null $tags
     *
     * @return Post
     */
    public function setTags(?Collection $tags): self
    {
        $this->tags = $tags;

        if (null !== $this->tags) {
            foreach ($this->tags as $tag) {
                $tag->addPost($this);
            }
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag): self
    {
        if (false === $this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPost($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return Post
     */
    public function removeTag(Tag $tag): self
    {
        if (false !== $this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);
        }

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     *
     * @return Post
     */
    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection|PostImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(PostImage $image): self
    {
        if (false === $this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPost($this);
        }

        return $this;
    }

    /**
     * @param PostImage $image
     *
     * @return Post
     */
    public function removeImage(PostImage $image): self
    {
        if (false !== $this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @param Collection|null $images
     *
     * @return Post
     */
    public function setImages(?Collection $images): self
    {
        $this->images = $images;

        foreach ($this->images as $image) {
            $image->setPost($this);
        }

        return $this;
    }
}
