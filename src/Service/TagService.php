<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Request\Tag\CreateTagRequest;
use App\Request\Tag\UpdateTagRequest;
use Doctrine\ORM\EntityManagerInterface;

class TagService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TagRepository
     */
    private $repo;

    /**
     * TagService constructor.
     *
     * @param EntityManagerInterface $em
     * @param TagRepository $repo
     */
    public function __construct(EntityManagerInterface $em, TagRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    /**
     * @param CreateTagRequest $dto
     *
     * @return Tag
     */
    public function create(CreateTagRequest $dto): Tag
    {
        $tag = Tag::createFromDTO($dto);

        $this->em->persist($tag);
        $this->em->flush();

        return $tag;
    }

    /**
     * @param UpdateTagRequest $dto
     * @param Tag $tag
     */
    public function update(UpdateTagRequest $dto, Tag $tag): void
    {
        $tag->updateFromDTO($dto);

        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * @param Tag $tag
     */
    public function delete(Tag $tag): void
    {
        $this->em->remove($tag);
        $this->em->flush();
    }

    /**
     * @return Tag[]
     */
    public function getAllTags(): array
    {
        return $this->repo->findAll();
    }
}
