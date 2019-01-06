<?php

namespace App\Service;

use App\Entity\Tag;
use App\Factory\TagFactory;
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
     * TagService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CreateTagRequest $dto
     *
     * @return Tag
     */
    public function create(CreateTagRequest $dto): Tag
    {
        $tag = TagFactory::createFromDTO($dto);

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
}
