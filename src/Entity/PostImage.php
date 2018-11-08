<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostImageRepository")
 * @Vich\Uploadable()
 */
class PostImage
{
    /**
     * @Groups({"post_list", "post_details"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @Vich\UploadableField(mapping="posts", fileNameProperty="filename")
     *
     * @var File
     */
    private $file;

    /**
     * @Groups({"post_list", "post_details"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename = null): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     *
     * @return PostImage
     */
    public function setFile(File $file = null): PostImage
    {
        $this->file = $file;

        return $this;
    }
}
