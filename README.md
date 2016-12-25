# language-detection
[![Build Status](https://travis-ci.org/patrickschur/language-detection.svg?branch=master)](https://travis-ci.org/patrickschur/language-detection)
[![codecov](https://codecov.io/gh/patrickschur/language-detection/branch/master/graph/badge.svg)](https://codecov.io/gh/patrickschur/language-detection)
[![Version](https://img.shields.io/packagist/v/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection)
[![Total Downloads](https://img.shields.io/packagist/dt/patrickschur/language-detection.svg?style=flat-square)](https://packagist.org/packages/patrickschur/language-detection)
[![Maintenance](https://img.shields.io/maintenance/yes/2017.svg?style=flat-square)](https://github.com/patrickschur/language-detection)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-FF.svg?style=flat-square)](http://php.net/)
[![License](https://img.shields.io/packagist/l/patrickschur/language-detection.svg?style=flat-square)](https://opensource.org/licenses/MIT)

A language detection library for PHP. Detect the language from a given text, based on [N-grams](https://en.wikipedia.org/wiki/N-gram).

Install via Composer
-
```bash
$ composer require patrickschur/language-detection
```
> **Note:** **language-detection** requires the [Multibyte String](http://php.net/manual/en/book.mbstring.php) extension in order to work. 

Basic Usage
-

Before we can recognize the language from a given text, we have to generate a language profile for each language.
From the beginning it comes with a pre-trained language profile ([`etc/_langs.json`](etc/_langs.json)).<br>
Also you can add new files to [`etc`](etc) or change existing ones.

If you have added your own files, you must first generate a language profile. Otherwise skip this step.
 
```php
use LanguageDetection\Trainer;
 
$t = new Trainer();
 
$t->learn();
```

If we have our language profile, we can classify texts by their language.
To detect the language correctly, the length of the input text should be at least some sentences.
 
```php
use LanguageDetection\Language;
 
$ld = new Language;
 
$ld->detect('Mag het een onsje meer zijn?')->all();
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "nb" => 0.48533333333333,
        "nn" => 0.48422222222222,
        "dk" => 0.46855555555556,
        "sv" => 0.46066666666667,
        "de" => 0.45544444444444,
        ...
    ]
*/
 
/* provide a whitelist */
$ld->detect('Mag het een onsje meer zijn?')->whitelist('de', 'nn', 'nl');
/*
    [
        "nl" => 0.65733333333333,
        "nn" => 0.48422222222222,
        "de" => 0.45544444444444
    ]
*/
 
/* provide a blacklist */
$ld->detect('Mag het een onsje meer zijn?')->blacklist('de', 'dk', 'sv');
/*
    [
        "nl" => 0.65733333333333,
        "af" => 0.50994444444444,
        "nb" => 0.48533333333333,
        "nn" => 0.48422222222222,
        ...
    ]
*/
```

Supported languages:
-

It supports up to now 77 languages.
If your language not supported, feel free to add your own language files.

- ab (Abkhaz)
- af (Afrikaans)
- am (Amharic)
- ar (Arabic)
- az-Cyrl (Azerbaijani, North (Cyrillic))
- az-Latn (Azerbaijani, North (Latin))
- be (Belarusan)
- bg (Bulgarian)
- bn (Bengali)
- co (Corsican)
- cs (Czech)
- cy (Welsh)
- de (German)
- dk (Danish)
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
- ga (Gaelic, Irish)
- gd (Gaelic, Scottish)
- gl (Galician)
- gn (Guarani)
- ha (Hausa)
- he (Hebrew)
- hi (Hindi)
- hr (Croatian)
- hu (Hungarian)
- hy (Armenian)
- ia (Interlingua)
- ig (Igbo)
- io (Ido)
- is (Icelandic)
- it (Italian)
- iu (Inuktitut)
- jp (Japanese)
- jv (Javanese)
- ka (Georgian)
- ko (Korean)
- ku (Kurdish)
- la (Latin)
- lg (Ganda)
- lo (Lao)
- lt (Lithuanian)
- lv (Latvian)
- mh (Marshallese)
- mn-Cyrl (Mongolian, Halh (Cyrillic))
- ms (Malay)
- mt (Maltese)
- nb (Norwegian, Nynorsk)
- nl (Dutch)
- nn (Norwegian, Bokmål)
- nv (Navajo)
- pl (Polish)
- pt (Portuguese)
- ro (Romanian)
- ru (Russian)
- sk (Slovak)
- sl (Slovene)
- so (Somali)
- sv (Swedish)
- th (Thai)
- tr (Turkish)
- ty (Tahitian)
- ug (Uyghur)
- uk (Ukrainian)
- uz (Uzbek)
- vi (Vietnamese)
- zh-Hans (Chinese, Mandarin (Simplified))
- zh-Hant (Chinese, Mandarin (Traditional))