<?php

namespace GoogleTranslate\Exception;

class InvalidAccessKeyException extends \InvalidArgumentException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid access key',
        $code = 1,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
}
