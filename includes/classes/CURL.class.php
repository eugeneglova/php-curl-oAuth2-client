<?php

    class CURL {
        protected $ch;
        public function __construct() {
            $this->ch = curl_init();
            $this->setopt(CURLOPT_HEADER, false);
            $this->setopt(CURLOPT_FOLLOWLOCATION, true);
            $this->setopt(CURLOPT_RETURNTRANSFER, true);
            $this->setopt(CURLOPT_SSL_VERIFYPEER, false);
            $this->setopt(CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
            $this->setopt(CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        }
        public function setopt($option, $value) {
            curl_setopt($this->ch, $option, $value);
        }
        public function exec($url, $data = false) {
            $this->setopt(CURLOPT_URL, $url);
            if ($data !== false) {
                $this->setopt(CURLOPT_POST, true);
                $this->setopt(CURLOPT_POSTFIELDS, $data);
            }
            $output = curl_exec($this->ch);
            curl_close($this->ch);
            return $output;
        }
    }