<?php

namespace App\Entity;

use App\Request\User\ChangeAvatarRequest;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use App\Security\Roles;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @Groups({"user_list", "user_details"})
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Groups({"user_list", "user_details"})
     */
    protected $username;

    /**
     * @var string
     *
     * @Groups({"user_details"})
     */
    protected $email;

    /**
     * @var string[]
     *
     * @Groups({"user_details"})
     */
    protected $roles;

    /**
     * @var string
     *
     * @Groups({"user_list", "user_details"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="avatars", fileNameProperty="avatar")
     */
    private $image;

    /**
     * @var Post[]
     *
     * @Groups({"user_details", "user_posts"})
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", orphanRemoval=true)
     */
    private $posts;

    /**
     * @var \DateTime
     *
     * @Groups({"user_details"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Groups({"user_details"})
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var Collection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->banned = false;
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @param CreateUserRequest $dto
     *
     * @return User
     */
    public static function createFromDTO(CreateUserRequest $dto): self
    {
        $user = new User;

        $user
            ->setUsername($dto->username)
            ->setEmail($dto->email)
            ->setPlainPassword($dto->password)
            ->addRole(Roles::ROLE_USER)
            ->setImage($dto->image)
            ->setCreatedAt($dto->createdAt)
            ->setUpdatedAt($dto->updatedAt)
            ->setEnabled(true);

        return $user;
    }

    /**
     * @param ChangePasswordRequest $dto
     */
    public function changePassword(ChangePasswordRequest $dto): void
    {
        $this
            ->setUpdatedAt($dto->updatedAt)
            ->setPlainPassword($dto->password);
    }

    /**
     * @param ChangeEmailRequest $dto
     */
    public function changeEmail(ChangeEmailRequest $dto): void
    {
        $this
            ->setUpdatedAt($dto->updatedAt)
            ->setEmail($dto->email);
    }

    /**
     * @param ChangeAvatarRequest $dto
     */
    public function changeAvatar(ChangeAvatarRequest $dto): void
    {
        $this
            ->setUpdatedAt($dto->updatedAt)
            ->setImage($dto->image);
    }

    public function promote(): void
    {
        $this->addRole(Roles::ROLE_ADMIN);
    }

    public function demote(): void
    {
        $this->removeRole(Roles::ROLE_ADMIN);
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
     * @return User
     */
    public function addPost(Post $post): self
    {
        if (false === $this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return User
     */
    public function removePost(Post $post): self
    {
        if (false !== $this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar(string $avatar = null): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param null|File $image
     *
     * @return User
     */
    public function setImage(?File $image = null): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getImage(): ?File
    {
        return $this->image;
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
     * @return User
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return User
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     *
     * @return User
     */
    public function addComment(Comment $comment): self
    {
        if (false === $this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return User
     */
    public function removeComment(Comment $comment): self
    {
        if (false !== $this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     *
     * @return User
     */
    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
}
