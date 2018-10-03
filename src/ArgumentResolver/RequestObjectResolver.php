<?php

namespace App\ArgumentResolver;

use App\Exception\InvalidRequestException;
use App\Request\RequestObject;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestObjectResolver implements ArgumentValueResolverInterface
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * RequestObjectResolver constructor.
     *
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     *
     */
    public function __construct(DenormalizerInterface $denormalizer, ValidatorInterface $validator)
    {
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), RequestObject::class);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     * @throws InvalidRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $data = $request->request->all();
        if (Request::METHOD_GET === $request->getMethod()) {
            $data = $request->query->all();
        }

        /** @var RequestObject $dto */
        $dto = $this->denormalizer->denormalize($data, $argument->getType());
        $this->validateDTO($dto);

        yield $dto;
    }

    /**
     * @param RequestObject $dto
     *
     * @throws InvalidRequestException
     */
    private function validateDTO(RequestObject $dto): void
    {
        $errors = $this->validator->validate($dto);
        if (0 !== count($errors)) {
            throw new InvalidRequestException($errors);
        }
    }
}
