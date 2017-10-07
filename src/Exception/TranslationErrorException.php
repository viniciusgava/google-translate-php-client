<?php

namespace GoogleTranslate\Exception;

class TranslationErrorException extends \DomainException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Translation Error',
        $code = 4,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
}
