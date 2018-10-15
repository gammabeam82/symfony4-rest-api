<?php

namespace  App\Event;

use App\Request\RequestObject;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class RequestObjectEvent extends Event
{
    /**
     * @var RequestObject
     */
    private $dto;

    /**
     * @var Request
     */
    private $request;

    /**
     * RequestObjectEvent constructor.
     *
     * @param Request $request
     * @param RequestObject $dto
     */
    public function __construct(Request $request, RequestObject $dto)
    {
        $this->request = $request;
        $this->dto = $dto;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return RequestObject
     */
    public function getRequestObject(): RequestObject
    {
        return $this->dto;
    }
}
