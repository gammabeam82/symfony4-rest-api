<?php

namespace  App\Event;

use App\Request\RequestObject;
use Symfony\Component\EventDispatcher\Event;

class RequestObjectEvent extends Event
{
    /**
     * @var RequestObject
     */
    private $dto;

    /**
     * RequestObjectEvent constructor.
     *
     * @param RequestObject $dto
     */
    public function __construct(RequestObject $dto)
    {
        $this->dto = $dto;
    }

    /**
     * @return RequestObject
     */
    public function getRequestObject(): RequestObject
    {
        return $this->dto;
    }
}
