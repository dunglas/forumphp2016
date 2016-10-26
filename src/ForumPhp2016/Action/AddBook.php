<?php

namespace ForumPhp2016\Action;

use ForumPhp2016\Domain\Entity\Book;
use ForumPhp2016\Domain\Exception\ValidationException;
use ForumPhp2016\Domain\Model\BookManager;
use ForumPhp2016\Responder\Responder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class AddBook
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
    public function __invoke(Request $request) : Response
    {
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json');
        $responder = $this->responder; // The invokable object must be assigned to a variable, not a propriety

        try {
            $this->bookManager->addBook($book);
        } catch (ValidationException $e) {
            return $responder($e->getConstraintViolationList());
        }

        return $responder($book);
    }
}
