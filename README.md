# Google Translate API PHP Client

[![Build Status](https://travis-ci.org/viniciusgava/google-translate-php-client.svg?branch=master)](https://travis-ci.org/viniciusgava/google-translate-php-client)

This project abstract the google translate api versio 2.0 in PHP. 
The lib has been refactored to support unit tests, composer, and a better architecture.

## Installation
Use composer to install the lib
```
composer require viniciusgava/google-translate-api
```

## Examples of Usage
### Translate with source language detection 
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

echo $client->translate('Hello world!', 'pt-br', $sourceLanguage);
// output: Olá Mundo!

echo $sourceLanguage;
// output: en
```

### Translate without source language detection
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

$sourceLanguage = 'pt-br';
echo $client->translate('Onde estou?', 'en', $sourceLanguage);
// output: Where am I?
```

### Translate bundle of texts with language detection
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

$texts = [
    '¿Cómo estás?',
    'あなたはどこに住んでいますか？',
    'Where are you going?',
    'Essa lib é muito legal!'
];

print_r($client->translate($texts, 'en', $sourceLanguage));
/* output:
    Array
    (
        [0] => How are you?
        [1] => Where do you live?
        [2] => Where are you going?
        [3] => This lib is really cool!
    )
*/

print_r($sourceLanguage);
/* output:
    Array
    (
        [0] => es
        [1] => ja
        [2] => en
        [3] => pt
    )
*/
```

### Detect language of a bundle of text
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

$texts = [
    '¿Cómo estás?',
    'あなたはどこに住んでいますか？',
    'Where are you going?',
    'Essa lib é muito legal!'
];

print_r($client->detect($texts));
/* output:
    Array
    (
        [0] => Array
        (
            [confidence] => 0.67241430282593
                [isReliable] =>
                [language] => es
            )
    
        [1] => Array
    (
        [confidence] => 1
                [isReliable] =>
                [language] => ja
            )
    
        [2] => Array
    (
        [confidence] => 0.67237991094589
                [isReliable] =>
                [language] => en
            )
    
        [3] => Array
    (
        [confidence] => 0.25708484649658
                [isReliable] =>
                [language] => pt
            )
    
    )
*/
```
### Detect language of a text
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

print_r($client->detect('Let\'s help the community!'));
/* output:
    Array
    (
        [confidence] => 0.26097252964973
        [isReliable] =>
        [language] => en
    )
*/
```
### List supported languages with name of language translated for a specific language
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY HERE');

print_r($client->languages('pt-br'));
/* output:
    Array
    (
        [0] => Array
            (
                [language] => af
                [name] => Africâner
            )

        [1] => Array
            (
                [language] => sq
                [name] => Albanês
            )

        [2] => Array
            (
                [language] => de
                [name] => Alemão
            )

        [3] => Array
            (
                [language] => ar
                [name] => Árabe
            )

        [4] => Array
            (
                [language] => hy
                [name] => Armênio
            )
        [5] => Array
            (
                [language] => zh
                [name] => Chinês (simplificado)
            )
        [6] => Array
            (
                [language] => fr
                [name] => Francês
            )
        .
        .
        .
    )
*/
```
### List supported languages
```php
<?php
require_once 'vendor/autoload.php';

$client = new \GoogleTranslate\Client('GOOGLE ACCESS KEY');

print_r($client->languages());
/* output:
    Array
    (
        [0] => Array
            (
                [language] => af
            )
    
        [1] => Array
            (
                [language] => am
            )
    
        [2] => Array
            (
                [language] => ar
            )
    
        [3] => Array
            (
                [language] => az
            )
    
        [4] => Array
            (
                [language] => be
            )
    
        [5] => Array
            (
                [language] => bg
            )
    
        [6] => Array
            (
                [language] => bn
            )
        .
        .
        .
    )
*/
```

## Version Guidance

| Version | Status     | Repo                 | PHP Version   |
|---------|------------|----------------------|---------------|
| 2.*     | Maintained |  [v2][client-2-repo] | >= 5.6 <= 7.1 |
| 3.*     | Latest     |  [v3][client-3-repo] | >= 7.2        |

[client-2-repo]: https://github.com/viniciusgava/google-translate-php-client/tree/2.1
[client-3-repo]: https://github.com/viniciusgava/google-translate-php-client
