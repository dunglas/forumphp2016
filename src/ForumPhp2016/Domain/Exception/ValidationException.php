<?php

namespace ForumPhp2016\Domain\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    private $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
       $this->constraintViolationList = $constraintViolationList;
    }

    public function getConstraintViolationList() : ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
