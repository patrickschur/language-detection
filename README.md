# language-detection
[![Build Status](https://travis-ci.org/patrickschur/language-detection.svg?branch=master)](https://travis-ci.org/patrickschur/language-detection)
[![codecov](https://codecov.io/gh/patrickschur/language-detection/branch/master/graph/badge.svg)](https://codecov.io/gh/patrickschur/language-detection)
[![Version](https://img.shields.io/packagist/v/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection)
[![Total Downloads](https://img.shields.io/packagist/dt/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection)
[![Maintenance](https://img.shields.io/maintenance/yes/2017.svg?style=flat-square)](https://github.com/patrickschur/language-detection)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-FF.svg?style=flat-square)](http://php.net/)
[![License](https://img.shields.io/packagist/l/patrickschur/language-detection.svg?style=flat-square)](https://opensource.org/licenses/MIT)

This library can detect the language of a given text string.
It can parse given training text in many different idioms into a sequence of [N-grams](https://en.wikipedia.org/wiki/N-gram) and builds a database file in JSON format to be used in the detection phase.
Then it can take a given text and detect its language using the database previously generated in the training phase.
The library comes with text samples used for training and detecting text in 104 languages.

Install via Composer
-
```bash
$ composer require patrickschur/language-detection
```
> **Note:** **language-detection** requires the [Multibyte String](http://php.net/manual/en/book.mbstring.php) extension in order to work. 

Basic Usage
-

If you have added your own files, you must first generate a language profile. 
Otherwise skip this step.
 
```php
use LanguageDetection\Trainer;
 
$t = new Trainer();
 
$t->learn();
```
 
Now, we can classify texts by their language.
To detect the language correctly, the length of the input text should be at least some sentences.
 
```php
use LanguageDetection\Language;
 
$ld = new Language;
 
$ld->detect('Mag het een onsje meer zijn?')->all();
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "br" => 0.49177777777778,
        "nb" => 0.48533333333333,
        "nn" => 0.48422222222222,
        "fy" => 0.47361111111111,
        "dk" => 0.46855555555556,
        "sv" => 0.46066666666667,
        "bi" => 0.45722222222222,
        "de" => 0.45544444444444,
        ...
    ]
*/
 
/* provide a whitelist */
$ld->detect('Mag het een onsje meer zijn?')->whitelist('de', 'nn', 'nl', 'af')->all();
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "nn" => 0.48422222222222,
        "de" => 0.45544444444444
    ]
*/
 
/* provide a blacklist */
$ld->detect('Mag het een onsje meer zijn?')->blacklist('dk', 'nb', 'de')->all();
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "br" => 0.49177777777778,
        "nn" => 0.48422222222222,
        "fy" => 0.47361111111111,
        "sv" => 0.46066666666667,
        "bi" => 0.45722222222222,
        ...
    ]
*/
 
/* specify the number of records to return */
$ld->detect('Mag het een onsje meer zijn?')->limit(0, 3)->all();
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "br" => 0.49177777777778
    ]
*/
 
$ld->detect('Mag het een onsje meer zijn?')->blacklist('af', 'dk', 'sv')->limit(0, 4)->all();
/*
    [
        "nl" => 0.65733333333333,
        "br" => 0.49177777777778,
        "nb" => 0.48533333333333,
        "nn" => 0.48422222222222
    ]
*/
```

Supported languages:
-
If your language not supported, feel free to add your own language files.

- ab (Abkhaz)
- af (Afrikaans)
- am (Amharic)
- ar (Arabic)
- ay (Aymara)
- az-Cyrl (Azerbaijani, North (Cyrillic))
- az-Latn (Azerbaijani, North (Latin))
- be (Belarusan)
- bg (Bulgarian)
- bi (Bislama)
- bn (Bengali)
- bo (Tibetan)
- br (Breton)
- bs-Cyrl (Bosnian (Cyrillic))
- bs-Latn (Bosnian (Latin))
- ca (Catalan)
- ch (Chamorro)
- co (Corsican)
- cr (Cree)
- cs (Czech)
- cy (Welsh)
- de (German)
- dk (Danish)
- dz (Dzongkha)
- el-monoton (Greek (monotonic))
- el-polyton (Greek (polytonic))
- en (English)
- eo (Esperanto)
- es (Spanish)
- et (Estonian)
- eu (Basque)
- fa (Persian)
- fi (Finnish)
- fj (Fijian)
- fo (Faroese)
- fr (French)
- fy (Frisian)
- ga (Gaelic, Irish)
- gd (Gaelic, Scottish)
- gl (Galician)
- gn (Guarani)
- gu (Gujarati)
- ha (Hausa)
- he (Hebrew)
- hi (Hindi)
- hr (Croatian)
- hu (Hungarian)
- hy (Armenian)
- ia (Interlingua)
- id (Indonesian)
- ig (Igbo)
- io (Ido)
- is (Icelandic)
- it (Italian)
- iu (Inuktitut)
- jp (Japanese)
- jv (Javanese)
- ka (Georgian)
- km (Khmer)
- ko (Korean)
- kr (Kanuri)
- ku (Kurdish)
- la (Latin)
- lg (Ganda)
- lo (Lao)
- lt (Lithuanian)
- lv (Latvian)
- mh (Marshallese)
- mn-Cyrl (Mongolian, Halh (Cyrillic))
- ms-Arab (Malay (Arabic))
- ms-Latn (Malay (Latin))
- mt (Maltese)
- nb (Norwegian, Nynorsk)
- nl (Dutch)
- nn (Norwegian, Bokmål)
- nv (Navajo)
- pl (Polish)
- pt-BR (Portuguese (Brazil))
- pt-PT (Portuguese (Portugal))
- ro (Romanian)
- ru (Russian)
- sk (Slovak)
- sl (Slovene)
- so (Somali)
- sq (Albanian)
- ss (Swati)
- sv (Swedish)
- th (Thai)
- tl (Tagalog)
- tr (Turkish)
- tt (Tatar)
- ty (Tahitian)
- ug-Arab (Uyghur (Arabic))
- ug-Latn (Uyghur (Latin))
- uk (Ukrainian)
- uz (Uzbek)
- ve (Venda)
- vi (Vietnamese)
- wa (Walloon)
- wo (Wolof)
- xh (Xhosa)
- yo (Yoruba)
- zh-Hans (Chinese, Mandarin (Simplified))
- zh-Hant (Chinese, Mandarin (Traditional))
