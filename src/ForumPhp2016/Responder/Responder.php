<?php

namespace ForumPhp2016\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Responder
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke($data)
    {
        $status = $data instanceof ConstraintViolationListInterface ? 400 : 200;
        $json = $this->serializer->serialize($data, 'json', ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]);

        return new JsonResponse($json, $status, ['X-Frame-Options' => 'deny'], true);
    }
}
