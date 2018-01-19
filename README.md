# language-detection
| Build Status | Code Coverage | Version | Total Downloads | Maintenance | Minimum PHP Version | License |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| [![Build Status](https://travis-ci.org/patrickschur/language-detection.svg?branch=master)](https://travis-ci.org/patrickschur/language-detection) | [![codecov](https://codecov.io/gh/patrickschur/language-detection/branch/master/graph/badge.svg)](https://codecov.io/gh/patrickschur/language-detection) | [![Version](https://img.shields.io/packagist/v/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection) | [![Total Downloads](https://img.shields.io/packagist/dt/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection) | [![Maintenance](https://img.shields.io/maintenance/yes/2018.svg?style=flat-square)](https://github.com/patrickschur/language-detection) | [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-4AC51C.svg?style=flat-square)](http://php.net/) | [![License](https://img.shields.io/packagist/l/patrickschur/language-detection.svg?style=flat-square)](https://opensource.org/licenses/MIT) |

This library can detect the language of a given text string.
It can parse given training text in many different idioms into a sequence of [N-grams](https://en.wikipedia.org/wiki/N-gram) and builds a database file in JSON format to be used in the detection phase.
Then it can take a given text and detect its language using the database previously generated in the training phase.
The library comes with text samples used for training and detecting text in 110 languages.

## Table of Contents
- [Installation with Composer](#installation-with-composer)
- [Basic Usage](#basic-usage)
- [API](#api)
- [Method Chaining](#method-chaining)
- [Array Access](#arrayaccess)
- [List of supported languages](#supported-languages)
- [Other languages](#other-languages)
- [FAQ](#faq)
- [Contributing](#contributing)
- [License](#license)

## Installation with Composer
> **Note:** This library requires the [Multibyte String](https://secure.php.net/manual/en/book.mbstring.php) extension in order to work. 

```bash
$ composer require patrickschur/language-detection
```

## Basic Usage
To detect the language correctly, the length of the input text should be at least some sentences.
```php
use LanguageDetection\Language;
 
$ld = new Language;
 
$ld->detect('Mag het een onsje meer zijn?')->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097,
    "af" => 0.51338709677419,
    "br" => 0.49634408602151,
    "nb" => 0.48849462365591,
    "nn" => 0.48741935483871,
    "fy" => 0.47822580645161,
    "dk" => 0.47172043010753,
    "sv" => 0.46408602150538,
    "bi" => 0.46021505376344,
    "de" => 0.45903225806452,
    [...]
)
```

## API

#### `__construct(array $result = [], string $dirname = '')`
You can pass an array of languages to the constructor. To compare the desired sentence only with the given languages.
This can dramatically increase the performance. 
The other parameter is optional and the name of the directory where the translations files are located.
```php
$ld = new Language(['de', 'en', 'nl']);
 
// Compares the sentence only with "de", "en" and "nl" language models.
$ld->detect('Das ist ein Test');
```
<hr style="background-color:#666"/>

#### `whitelist(string ...$whitelist)`
Provide a whitelist. Returns a list of languages, which are required.
```php
$ld->detect('Mag het een onsje meer zijn?')->whitelist('de', 'nn', 'nl', 'af')->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097,
    "af" => 0.51338709677419,
    "nn" => 0.48741935483871,
    "de" => 0.45903225806452
)
```
<hr style="background-color:#666"/>

#### `blacklist(string ...$blacklist)`
Provide a blacklist. Removes the given languages from the result.
```php
$ld->detect('Mag het een onsje meer zijn?')->blacklist('dk', 'nb', 'de')->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097,
    "af" => 0.51338709677419,
    "br" => 0.49634408602151,
    "nn" => 0.48741935483871,
    "fy" => 0.47822580645161,
    "sv" => 0.46408602150538,
    "bi" => 0.46021505376344,
    [...]
)
```
<hr style="background-color:#666"/>

#### `bestResults()`
Returns the best results.
```php
$ld->detect('Mag het een onsje meer zijn?')->bestResults()->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097
)
```
<hr style="background-color:#666"/>

#### `limit(int $offset, int $length = null)`
You can specify the number of records to return. For example the following code will return the top three entries.
```php
$ld->detect('Mag het een onsje meer zijn?')->limit(0, 3)->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097,
    "af" => 0.51338709677419,
    "br" => 0.49634408602151
)
```
<hr style="background-color:#666"/>

#### `close()`
Returns the result as an array.
```php
$ld->detect('This is an example!')->close();
```
Result:
```text
Array
(
    "en" => 0.5889400921659,
    "gd" => 0.55691244239631,
    "ga" => 0.55376344086022,
    "et" => 0.48294930875576,
    "af" => 0.48218125960061,
    [...]
)
```
<hr style="background-color:#666"/>

#### `setTokenizer(TokenizerInterface $tokenizer)`
The script use a tokenizer for getting all words in a sentence. 
You can define your own tokenizer to deal with numbers for example.
```php
$ld->setTokenizer(new class implements TokenizerInterface
{
    public function tokenize(string $str): array 
    {
        return preg_split('/[^a-z0-9]/u', $str, -1, PREG_SPLIT_NO_EMPTY);
    }
});
```
This will return only characters from the alphabet in lowercase and numbers between 0 and 9.
<hr style="background-color:#666"/>

#### `__toString()`
Returns the top entrie of the result. Note the `echo` at the beginning.
```php
echo $ld->detect('Das ist ein Test.');
```
Result:
```text
de
```
<hr style="background-color:#666"/>

#### `jsonSerialize()`
Serialized the data to JSON.
```php
$object = $ld->detect('Tere tulemast tagasi! Nägemist!');
 
json_encode($object, JSON_PRETTY_PRINT);
```
Result:
```text
{
    "et": 0.5224748810153358,
    "ch": 0.45817028027498674,
    "bi": 0.4452670544685352,
    "fi": 0.440983606557377,
    "lt": 0.4382866208355367,
    [...]
}
```
<hr style="background-color:#666"/>

## Method chaining
You can also combine methods with each other.
The following example will remove all entries specified in the blacklist and returns only the top four entries.
```php 
$ld->detect('Mag het een onsje meer zijn?')->blacklist('af', 'dk', 'sv')->limit(0, 4)->close();
```
Result:
```text
Array
(
    "nl" => 0.66193548387097
    "br" => 0.49634408602151
    "nb" => 0.48849462365591
    "nn" => 0.48741935483871
)
```
<hr style="background-color:#666"/>

## ArrayAccess
You can also access the object directly as an array.
```php
$object = $ld->detect(Das ist ein Test');
 
echo $object['de'];
echo $object['en'];
echo $object['xy']; // does not exists
```
Result:
```text
0.6623339658444
0.56859582542694
NULL
```
<hr style="background-color:#666"/>

## Supported languages
The library currently supports 110 languages.
To get an overview of all supported languages please have a look at [here](resources/README.md).
<hr style="background-color:#666"/>

## Other languages
The library is trainable which means you can change, remove and add your own language files to it.
If your language not supported, feel free to add your own language files.
To do that, create a new directory in `resources` and add your training text to it.
> **Note:** The training text should be a **.txt** file.

#### Example
```text
|- resources
    |- ham
        |- ham.txt
    |- spam
        |- spam.txt
```
As you can see, we can also used it to detect spam or ham.

When you stored your translation files outside of `resources`, you have to specify the path.
```php
$t->learn('YOUR_PATH_HERE');
```

Whenever you change one of the translation files you must first generate a language profile for it.
This may take a few seconds.
```php
use LanguageDetection\Trainer;
 
$t = new Trainer();
 
$t->learn();
```
Remove these few lines after execution and now we can classify texts by their language with our own training text.
<hr style="background-color:#666"/>

## FAQ
#### How can I improve the detection phase?
To improve the detection phase you have to use more n-grams. But be careful this will slow down the script.
I figured out that the detection phase is much better when you are using around 9.000 n-grams (default is 310).
To do that look at the code right below:
```php
$t = new Trainer();
 
$t->setMaxNgrams(9000);
 
$t->learn();
```
First you have to train it. 
Now you can classify texts like before but you must specify how many n-grams you want to use.
```php
$ld = new Language();
 
$ld->setMaxNgrams(9000);
  
// "grille pain" is french and means "toaster" in english
var_dump($ld->detect('grille pain')->bestResults());
```
Result:
```text
class LanguageDetection\LanguageResult#5 (1) {
  private $result =>
  array(2) {
    'fr' =>
    double(0.91307037037037)
    'en' =>
    double(0.90623333333333)
  }
}
```
#### Is the detection process slower if language files are very big?
No it is not. The trainer class will only use the best 310 n-grams of the language.
If you don't change this number or add more language files it will not affect the performance. 
Only creating the N-grams is slower. However, the creation of N-grams must be done only once.
The detection phase is only affected when you are trying to detect big chunks of texts.
> **Summary**: The training phase will be slower but the detection phase remains the same.

## Contributing
Feel free to contribute. Any help is welcome.

## License
This projects is licensed under the terms of the MIT license.
