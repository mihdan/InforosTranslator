InforosTranslator
=================

Доступ к платному API Google Translate

Как это работает
================

```php
<?php
$key = 'api key';
$query = 'привет мир';
$source = 'ru';
$target = 'en';
$translator = new InforosTranslator();
$translator->apiKey = $key;
$response = $translator->translate($query, $source, $target);
echo json_encode($response);
?>
```