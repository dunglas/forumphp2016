<?php

namespace ForumPhp2016\Action;

use ForumPhp2016\Domain\Entity\Book;
use ForumPhp2016\Domain\Exception\ValidationException;
use ForumPhp2016\Domain\Model\BookManager;
use ForumPhp2016\Responder\Responder;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class AddBook implements ServerMiddlewareInterface
{
    private $responder;
    private $serializer;
    private $bookManager;

    public function __construct(Responder $responder, SerializerInterface $serializer, BookManager $bookManager) {
        $this->responder = $responder;
        $this->serializer = $serializer;
        $this->bookManager = $bookManager;
    }

    /**
     * @Route("/adr/books", methods={"POST"})
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response = null) : ResponseInterface
    {
        $book = $this->serializer->deserialize($request->getBody()->getContents(), Book::class, 'json');
        $responder = $this->responder; // The invokable object must be assigned to a variable, not a propriety

        try {
            $this->bookManager->addBook($book);
        } catch (ValidationException $e) {
            return $responder($e->getConstraintViolationList());
        }

        return $responder($book, $response);
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        $response = $delegate->process($request);
        return $this->__invoke($request, $response);
    }
}
