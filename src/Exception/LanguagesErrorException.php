<?php

namespace GoogleTranslate\Exception;

class LanguagesErrorException extends \DomainException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Languages Error',
        $code = 5,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
}
