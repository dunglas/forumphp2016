<?php

namespace AppBundle\Controller;

use ForumPhp2016\Domain\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * @Route("/books")
     * @Method("POST")
     */
    public function addBook(Request $request)
    {
        $book = $this->get('serializer')->deserialize($request->getContent(), Book::class, 'json');

        if (count($errors = $this->get('validator')->validate($book))) {
            return $this->json($errors, 400, ['X-Frame-Options' => 'deny']);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($book);
        $manager->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf('Book #%d added', $book->getId()))
            ->setFrom('bot@example.com')
            ->setTo('kevin@example.com')
            ->setBody(sprintf('A new book titled "%s" was added.', $book->getTitle()))
        ;
        $this->get('mailer')->send($message);

        // TODO: Send a SMS here

        return $this->json($book, 200, ['X-Frame-Options' => 'deny']);
    }

    // ...
}
