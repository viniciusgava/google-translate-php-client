<?php

namespace GoogleTranslate\Exception;

class InvalidLanguageException extends \InvalidArgumentException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid language',
        $code = 3,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
