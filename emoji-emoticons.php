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

  require 'emoji_emoticons-codes.php';

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

      $code_k = str_replace(':', '', $code);

      $image = Emoji_Emoticons_Codes::$codes[$code_k];

      if(empty($image)) return $code;

      // Fix for Windows...
      if(preg_match('/\//', dirname(__FILE__))) $dir = array_reverse(explode('/', dirname(__FILE__)));
      else $dir = array_reverse(explode('\\', dirname(__FILE__)));

      if($dir[0] == 'trunk') $dir[0] = $dir[1];

      $url = plugins_url($dir[0].'/emojis/'.$image);

      return '<img src="'.$url.'" class="emoji-smiley" width="20" height="20" title="'.$code_k.'" alt="'.$code_k.'" />'; exit;

    }

    public static function beforeFilter( $content ) {

      if(preg_match_all('/:\S{1,}:/', $content, $results))
      {
        foreach($results as $result) {
          foreach($result as $code) $content = str_replace($code, self::emoticon($code), $content);
        }
      }

      if(preg_match_all('/:\S{1,}:/', $content, $results))
      {
        foreach($results as $result) {
          foreach($result as $code) $content = str_replace($code, str_replace('\'', '', $code), $content);
        }
      }

      return $content;

    }

  }

  function Emoji_Emoticons() { return Emoji_Emoticons::getInstance(); }
  add_action( 'plugins_loaded', 'Emoji_Emoticons' );

}