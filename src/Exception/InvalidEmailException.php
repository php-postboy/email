<?php

declare(strict_types=1);

namespace Postboy\Email\Exception;

use InvalidArgumentException;
use Throwable;

class InvalidEmailException extends InvalidArgumentException
{
    final public function __construct(string $email, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('email %s is invalid', $email);
        parent::__construct($message, $code, $previous);
    }
}
