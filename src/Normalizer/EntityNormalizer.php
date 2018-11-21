<?php

namespace App\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntityNormalizer extends ObjectNormalizer
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * EntityNormalizer constructor.
     *
     * @param EntityManagerInterface $em
     * @param null|ClassMetadataFactoryInterface $classMetadataFactory
     * @param null|NameConverterInterface $nameConverter
     * @param null|PropertyAccessorInterface $propertyAccessor
     * @param null|PropertyTypeExtractorInterface $propertyTypeExtractor
     */
    public function __construct(
        EntityManagerInterface $em,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);

        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 0 === strpos($type, 'App\\Entity\\') && false !== is_numeric($data);
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->em->find($class, $data);
    }
}
