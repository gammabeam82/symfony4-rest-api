<?php

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return false !== $data instanceof \DateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->format(\DateTime::ISO8601);
    }
}
