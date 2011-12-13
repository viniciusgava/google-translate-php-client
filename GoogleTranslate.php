<?php

/**
 * Google-Translate-API
 * New API Library for Google translate V2 in PHP 
 * @link https://github.com/viniciusgava/Google-Translate-API
 * @license http://www.gnu.org/copyleft/gpl.html
 * @version 1.0
 * @author Vinicius Gava (gava.vinicius@gmail.com)
 */
class GoogleTranslate {

    /**
     * URI API
     * @var string
     */
    private $apiUri = 'https://www.googleapis.com/language/translate/v2';

    /**
     * Access Key to API
     * @var string 
     */
    private $accessKey = '';

    /**
     *
     * @var Curl
     */
    private $connect;

    /**
     * list parameters used in get request
     * @var array
     */
    private $parameters = array();

    /**
     * Service translate text
     */
    CONST SERVICE_TRANSLATE = 'translate';
    /**
     * Service detect language
     */
    CONST SERVICE_DETECT = 'detect';
    /**
     * Service language support
     */
    CONST SERVICE_LANGUAGE = 'language';

    public function __construct($accessKey) {
        $this->setAccessKey($accessKey);
    }
    /**
     * Set access key
     * @param string $key 
     */
    public function setAccessKey($key) {
        if (strlen($key) == 39) {
            $this->accessKey = $key;
        } else {
            throw new GoogleTranslateInvalidKey();
        }
    }

    /**
     * Translate text
     * @param string|array $text The text to be translated
     * @param string $targetLanguage The language to translate the source text into
     * @param string|null|array $sourceLanguage The language of the source text. If a language is not specified, the system will attempt to identify the source language automatically
     */
    public function translate($text, $targetLanguage, &$sourceLanguage = null) {
        if ($this->isValid($text, $targetLanguage, $sourceLanguage)) {
            reset($text);
            //add keyAccess
            $this->addQueryParam('key', $this->accessKey);
            //add text to be translate
            $this->addQueryParam('q', $text);
            //add target language
            $this->addQueryParam('target', $targetLanguage);
            //if source not null, add param to query
            if(!is_null($sourceLanguage)){
            $this->addQueryParam('source', $sourceLanguage);                
            }
            //init connect
            $this->initConnect();
            //get content
            $result = $this->execConnect();
            //close connect
            $this->closeConnect();
            //verify this is multiple text
            if (!is_array($text)) {
                //get only info necessary 
                $result = current($result->translations);
                //return by reference the language in case detected language
                $sourceLanguage = $result->detectedSourceLanguage;
                //return translate
                return $result->translatedText;
            } else {
                //this is multiple text
                //get only info necessary
                $result = $result->translations;
                //save translate list
                $arrTranslateReturn = array();
                //save source list
                $arrSourceReturn = array();
                //get translates
                foreach ($result as $itemResult) {
                    $arrTranslateReturn[] = $itemResult->translatedText;
                    $arrSourceReturn[] = $itemResult->detectedSourceLanguage;
                }
                //return by reference the language in case detected language
                $sourceLanguage = $arrSourceReturn;
                //return list of translate
                return $arrTranslateReturn;
            }
        } else {
            return false;
        }
    }

    /**
     * Deletect Language
     * @param string|array $text The text or list the text to be detect
     * @param null|string|array $isReliable is reliable
     * @return string|array language or list of language detected 
     */
    public function detect($text, &$isReliable = null) {
        if ($this->isValid($text, null, null, false)) {
            reset($text);

            //add keyAccess
            $this->addQueryParam('key', $this->accessKey);
            //add text to be translate
            $this->addQueryParam('q', $text);
            //init connect
            $this->initConnect(self::SERVICE_DETECT);
            //get content
            $result = $this->execConnect();
            //close connect
            $this->closeConnect();
            //verify this is multiple text
            if (count($result->detections) == 1) {
                //get only info necessary 
                $result = current(current($result->detections));
                //return by reference the language is realiable
                $isReliable = $result->isReliable;
                //return translate
                return $result->language;
            } else {
                //this is multiple text
                //get only info necessary
                $result = $result->detections;
                //save is reliable list
                $arrIsReliable = array();
                //save detect language list
                $arrSourceReturn = array();
                //get translates
                foreach ($result as $itemResult) {
                    $itemResult = current($itemResult);
                    $arrIsReliable[] = $itemResult->isReliable;
                    $arrSourceReturn[] = $itemResult->language;
                }
                //return by reference the language is realiable
                $isReliable = $arrIsReliable;
                //return list of detect language
                return $arrSourceReturn;
            }
        } else {
            return false;
        }
    }

    /**
     * Language support
     * @param string|null $target language target
     */
    public function languageSupport($target = null) {
        //add keyAccess
        $this->addQueryParam('key', $this->accessKey);
        if(!is_null($target)){
            if ($this->validLanguage($target)) {
                $this->addQueryParam('target', $target);                
            }else{
                return false;
            }            
        }
        //init connect
        $this->initConnect(self::SERVICE_LANGUAGE);
        //get content
        $result = $this->execConnect();
        //close connect
        $this->closeConnect();
        
        //get only info necessary
        $result = $result->languages;        
        //return list of language support
        return $result;
        
    }

    /**
     * Validate info 
     * @param string|array $text The text or list the text to be validate
     * @param string $targetLanguage target language to be validate
     * @param string $sourceLanguage source language to be validate
     * @param boolean $targetRequired the target language is required?
     * @return boolean 
     */
    private function isValid(&$text, $targetLanguage = null, $sourceLanguage = null, $targetRequired = true) {
        //in case of numeric only in text, return false
        if (!is_array($text)) {
            $text = array($text);
        }
        //valid list of translate
        foreach ($text as $keyText => $oneText) {
            //is numeric?
            if (is_numeric($oneText) || strlen($oneText) < 2) {
                //remove from list translate
                unset($text[$keyText]);
            }
        }
        //no text valid?
        if (count($text) <= 0) {
            return false;
        }
        //target required?
        if ($targetRequired) {
            //is valid language target
            if (!$this->validLanguage($targetLanguage)) {
                return false;
            }
        }
        //is valid language source
        if (!is_null($sourceLanguage)) {
            if (!$this->validLanguage($sourceLanguage)) {
                return false;
            }
        }
        //valid!
        return true;
    }

    /**
     * validate language
     * @param string $lang language text to be validate
     * @return boolean 
     */
    private function validLanguage($lang) {
        $regexpValidLanguage = '%([a-z]{2})(-[a-z]{2})?%';
        if (preg_match($regexpValidLanguage, $lang) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Init the connect with parameters find
     */
    private function initConnect($service = self::SERVICE_TRANSLATE) {
        //choose service
        switch ($service) {
            case self::SERVICE_DETECT : $url = $this->apiUri . '/detect';
                break;
            case self::SERVICE_LANGUAGE : $url = $this->apiUri . '/languages';
                break;
            case self::SERVICE_TRANSLATE : default: $url = $this->apiUri;
        }
        //case exists parameters, add to url
        if (count($this->parameters) > 0) {
            $url .= '?';
            //add for each item 
            foreach ($this->parameters as $keyParam => $param) {
                //used in case de multiple text translate
                if (is_array($param)) {
                    foreach ($param as $subParam) {
                        $url .= '&' . urlencode($keyParam) . '=' . urlencode($subParam);
                    }
                } else {
                    $url .= '&' . urlencode($keyParam) . '=' . urlencode($param);
                }
            }
        }
        //init curl
        $this->connect = curl_init($url);
        //return data receive
        curl_setopt($this->connect, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Add Query param in connect
     * @param type $key
     * @param type $value 
     */
    private function addQueryParam($key, $value) {
        //remove possible whitespaces, utf8 encode AND add to params list
        if (is_array($value)) {
            foreach ($value as $keyValue => $itemValue) {
                $value[$keyValue] = utf8_encode($itemValue);
            }
        } else {
            $value = utf8_encode($value);
        }
        //add to param list
        $this->parameters[utf8_encode(str_replace(' ', '', $key))] = $value;
    }

    /**
     * Close the connect
     */
    private function closeConnect() {
        //close curl connect
        curl_close($this->connect);
        //clear params to next request
        $this->parameters = array();
    }
    /**
     * Execute connect and return array with data
     * @return array  
     */
    private function execConnect() {
        //exec curl
        $result = curl_exec($this->connect);
        //transform json in stdClass
        $result = json_decode($result);

        //get request info
        $arrInfo = curl_getinfo($this->connect);
        //found?
        if ($arrInfo['http_code'] == 404) {
            //no connect
            throw new GoogleTranslateNotFoundException();
        }

        if (($arrInfo['http_code'] == 200 || $arrInfo['http_code'] == 304) && !isset($result->error)) {
            //request ok, return data
            return $result->data;
        } else {
            //invalid key
            throw new GoogleTranslateInvalidKeyException();
        }
    }

}

/**
 * Google Translate Exception Invalid Access Key
 */
class GoogleTranslateInvalidKeyException extends Exception {

    function __construct() {
        parent::__construct('Invalid Access Key');
    }

}

/**
 * Google Translate Exception Not found 404, probable problem in connect internert
 */
class GoogleTranslateNotFoundException extends Exception {
    
    function __construct() {
        parent::__construct('Not Found Request', 404);
    }
}
