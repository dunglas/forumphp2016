<?php

namespace ForumPhp2016\Responder;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Responder
{
    private $serializer;
    private $responseFactory;
    private $streamFactory;

    public function __construct(
        SerializerInterface $serializer,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->serializer = $serializer;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function __invoke($data, ResponseInterface $response = null) : ResponseInterface
    {
        $status = $data instanceof ConstraintViolationListInterface ? 400 : 200;
        $json = $this->serializer->serialize($data, 'json', ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]);

        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $json);

        if (null === $response) {
            $response = $this->responseFactory->createResponse($status);
        } else {
            $response = $response->withStatus($status);
        }

        return $response
            ->withBody($this->streamFactory->createStream($resource))
            ->withHeader('X-Frame-Options', 'deny')
        ;
    }
}
