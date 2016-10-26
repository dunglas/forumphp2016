<?php

namespace ForumPhp2016\Domain\Notifier;

use ForumPhp2016\Domain\Entity\Book;

class Notifier
{
    private $mailer;
    private $from;
    private $to;

    public function __construct(\Swift_Mailer $mailer, string $from = 'bot@example.com', string $to = 'kevin@example.com')
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
    }

    public function notifyNewBook(Book $book)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf('Book #%d added', $book->getId()))
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody(sprintf('A new book titled "%s" was added.', $book->getTitle()))
        ;
        $this->mailer->send($message);

        // Introduce an interface and use a chain pattern to support other notification mechanisms like SMS
    }
}
