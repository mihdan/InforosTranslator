InforosTranslator
=================

Класс для доступа к платному API Google Translate. Содержит три основных метода: languages(), detect(), translate()

#Как это работает

```php
<?php
$key = 'api key';
$query = 'привет мир';
$source = 'ru';
$target = 'en';
$translator = new InforosTranslator();
$translator->apiKey = $key;
$response = $translator->translate($query, $source, $target);
echo $response;
?>
```