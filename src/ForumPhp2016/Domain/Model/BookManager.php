<?php

namespace ForumPhp2016\Domain\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use ForumPhp2016\Domain\Entity\Book;
use ForumPhp2016\Domain\Exception\ValidationException;
use ForumPhp2016\Domain\Notifier\Notifier;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookManager
{
    private $validator;
    private $doctrine;
    private $notifier;

    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, Notifier $notifier)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->notifier = $notifier;
    }

    public function addBook(Book $book)
    {
        if (count($errors = $this->validator->validate($book))) {
            throw new ValidationException($errors);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($book);
        $manager->flush();

        $this->notifier->notifyNewBook($book);
    }
}
