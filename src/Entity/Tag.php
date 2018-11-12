<?php

namespace App\Entity;

use App\Request\Tag\CreateTagRequest;
use App\Request\Tag\UpdateTagRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @var int
     *
     * @Groups({"tag_list", "tag_details", "post_details"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"tag_list", "tag_details", "post_details"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Post[]
     *
     * @Groups({"tag_details", "tag_posts"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @param CreateTagRequest $dto
     *
     * @return Tag
     */
    public static function createFromDTO(CreateTagRequest $dto): self
    {
        $tag = new Tag();
        $tag->setName($dto->name);

        return $tag;
    }

    /**
     * @param UpdateTagRequest $dto
     */
    public function updateFromDTO(UpdateTagRequest $dto): void
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
     * @return Tag
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
     * @return Tag
     */
    public function addPost(Post $post): self
    {
        if (false === $this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return Tag
     */
    public function removePost(Post $post): self
    {
        if (false !== $this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }
}
