<?php
/**
 * Переводчик текстов
 * @docs    https://developers.google.com/translate/v2/getting_started?hl=ru
 * @author  kobzarev@inforos.ru
 */
class InforosTranslator
{

    private $_apiKey;
    private $_referer;
    private $_timeout = 3;

    const API_GATEWAY = 'https://www.googleapis.com/language/translate/v2';

    /**
     * Конструктор
     * @param string $key - можно передать ключ,
     * либо позже присовить его через свойство
     */
    public function __construct($key = '')
    {
        if (!empty($key)) {
            if (strlen($key) == 39) {
                $this->_apiKey = $key;
            } else {
                $this->_raiseError('Неверный API key');
            }
        }
    }

    /**
     * Перегрузка закрытых свойств
     * @param string $name - свойство
     * @param string $value - значение
     */
    public function __set($name, $value)
    {
        $property = '_' . $name;
        if (property_exists(__CLASS__, $property)) {
            $this->$property = $value;
        } else {
            $this->_raiseError('Передано неизвестное свойство ' . $name);
        }
    }

    /**
     * Перевод строки с одного языка на другой
     * Returns text translations from one language to another
     * @param string $query - строка для перевода
     * @param string $source - язык источника
     * @param string $target - целевой язык
     * @param string $format - формат исходного текста (html | text)
     * @param boolean $prettyprint - ставить ли отступы и переводы в выводе
     */
    public function translate($query, $source = 'ru', $target = 'en', $format = 'html', $prettyprint = false)
    {
        return $this->_load($this->_buildQuery(
            array(
                'q' => $query,
                'source' => $source,
                'target' => $target,
                'format' => $format,
                'prettyprint' => $prettyprint
            )
        ));
    }

    /**
     * Список поддерживаемых языков
     * List the source/target languages supported by the API
     * @param string $target - язык вывода результатов
     * @param boolean $prettyprint - форматировать ли результат
     */
    public function languages($target = 'ru', $prettyprint = false)
    {
        return $this->_load($this->_buildQuery(
            array(
                'target' => $target,
                'prettyprint' => $prettyprint
            ),  'languages'
        ));
    }

    /**
     * Определить язык текста
     * Detect the language of text
     * @param string $query - строка для определения
     * @param boolean $prettyprint - форматировать ли результат
     */
    public function detect($query, $prettyprint = false)
    {
        return $this->_load($this->_buildQuery(
            array(
                'q' => $query,
                'prettyprint' => $prettyprint
            ),  'detect'
        ));
    }

    /**
     * Строим полный путь до API
     * @param array $data - массив для построения
     * @return string
     */
    private function _buildQuery($data = array(), $method = '')
    {
        // Добавить ключ
        $data['key'] = $this->_apiKey;

        // Сформировать полный URL
        $url = self::API_GATEWAY . '/' . $method . '?' . http_build_query($data);
        //echo $url;
        return $url;
    }

    /**
     * Загрузить URL
     * @param string $url - адрес
     * @return json
     */
    private function _load($url)
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'timeout' => $this->_timeout,
                'Referer' => $this->_referer
            )
        );
        $json = @file_get_contents($url, false, stream_context_create($opts));
        if (empty($json)) $this->_raiseError('Ошибка запроса');
        $text = json_decode($json);

        return $text->data->translations[0]->translatedText;
    }

    /**
     * Вывод сообщений об ошибке
     * @param $error - текст ошибки
     */
    private function _raiseError($error)
    {
        die($error);
    }
}

?>