<?php

namespace App\Normalizer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ArrayCollectionNormalizer extends ObjectNormalizer implements DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return false !== strpos($type, '[]') && false !== is_array($data);
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $result = new ArrayCollection();

        foreach ($data as $item) {
            $entity = $this->denormalizer->denormalize($item, $this->getClassName($class), $format, $context);
            $result->add($entity);
        }

        return $result;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function getClassName(string $class): string
    {
        return str_replace('[]', '', $class);
    }
}
