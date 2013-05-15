<?php
    class HTML_Parser {
        protected $content;
        public function __construct($content) {
            $this->set_content($content);
        }
        public function set_content($content) {
            if ($content) {
                $this->content = $content;
            }
        }
        public function get_first_form($content = false) {
            $this->set_content($content);
            if (preg_match('/<form.*<\/form>/U', $this->content, $matches)) {
                return $this->content = $matches[0];
            } else {
                die('can\'t find form');
            }
        }
        public function get_form_url($content = false) {
            $this->set_content($content);
            if (preg_match('/<form[^>]*\saction=[\'"]([^\'"]*)[\'"]/', $this->content, $matches)) {
                return $url = html_entity_decode($matches[1]);
            } else {
                die('can\'t find form url');
            }
        }
        public function get_hidden_fields($content = false) {
            $this->set_content($content);
            $data = array();
            if (preg_match_all('/<input[^>]*\stype=[\'"]hidden[\'"]\s*[^>]*\sname=[\'"]([^\'"]*)[\'"]\s*[^>]*\svalue=[\'"]([^\'"]*)[\'"]/', $this->content, $matches)) {
                foreach ($matches[1] as $key => $value) {
                    $data[$value] = html_entity_decode($matches[2][$key]);
                }
            } else {
                die('can\'t find hidden fields');
            }
            return $data;
        }
    }
