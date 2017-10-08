<?php

namespace GoogleTranslate\Exception;

class InvalidTextException extends \InvalidArgumentException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid text',
        $code = 2,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
