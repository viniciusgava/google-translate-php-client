<?php

namespace GoogleTranslate\Exception;

class TranslateErrorException extends \DomainException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Translate Error',
        $code = 4,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
