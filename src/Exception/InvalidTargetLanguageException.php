<?php

namespace GoogleTranslate\Exception;

class InvalidTargetLanguageException extends InvalidLanguageException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Invalid target language',
        $code = 3,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
