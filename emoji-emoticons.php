<?php

/*
Plugin Name: Emoji Emoticons
Plugin URI: http://wordpress.org/extend/plugins/emoji-emoticons/
Description: Support for Emoji Emoticons: http://www.emoji-cheat-sheet.com/
Version: 0.1
Author: Guilherme Baptista
Author URI: http://gbaptista.com
License: MIT
*/

if(!class_exists('Emoji_Emoticons')) {

  class Emoji_Emoticons {

    private static $instance;

    public static function getInstance() {
      if(!isset(self::$instance)) self::$instance = new self;
      return self::$instance;
    }

    private function __construct() {
      add_filter('the_content', array(__CLASS__, 'beforeFilter'), -1);
      add_filter('the_excerpt', array(__CLASS__, 'beforeFilter'), -1);
      add_filter('comment_text', array(__CLASS__, 'beforeFilter'), -1);
    }

    private function emoticon($code)
    {
      return $code;
    }

    public static function beforeFilter( $content ) {

      if(preg_match_all('/:\S{1,}:/', $content, $results))
      {
        foreach($results as $result) {
          foreach($result as $code) $content = str_replace($code, self::emoticon($code), $content);
        }
      }

      return $content;

    }

  }

  function Emoji_Emoticons() { return Emoji_Emoticons::getInstance(); }
  add_action( 'plugins_loaded', 'Emoji_Emoticons' );

}