<?php

namespace GoogleTranslate\Exception;

class InvalidSourceLanguageException extends InvalidLanguageException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid source language',
        $code = 3,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
