<?php
namespace Success\TreasureBundle\Exception;

class NotEnoughAmountException extends \RuntimeException
{
    public function __construct($message)
    {
        return parent::__construct($message, -1, null);
    }
}
