<?php

namespace GoogleTranslate;

interface LanguagesInterface
{
    /**
     * List language supports
     *
     * Return example:
     * [
     *      (string) 'language' => (string) Supported language code, generally consisting of its ISO 639-1 identifier,
     *      (string) 'name' => (string) Human readable name of the language localized to the target language
     * ]
     *
     * @param string $targetLanguage Target language. ie: pt, en, es
     * @return array
     *
     * @throws Exception\InvalidTargetLanguageException
     * @throws Exception\TranslationErrorException
     */
    public function languages($targetLanguage = null);
}
