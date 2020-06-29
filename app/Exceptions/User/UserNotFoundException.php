<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseException;
use Exception;
use Throwable;

/**
 * UserNotFoundException
 */
class UserNotFoundException extends Exception
{

    /**
     * Message
     *
     * @var string
     */
    protected $message = 'User not found: ';

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message . $message, $code, $previous);
    }
}
