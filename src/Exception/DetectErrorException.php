<?php

namespace GoogleTranslate\Exception;

class DetectErrorException extends \DomainException
{
    /** @inheritdoc */
    public function __construct(
        $message = 'Detect Error',
        $code = 6,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
