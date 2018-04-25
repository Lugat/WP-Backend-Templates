<?
  
  namespace Jinx;
  
  abstract class Plugin
  {
    
    public static function parseBlocks($content)
    {

      $regex = '/<!--:([a-z]{3,})-->(.*)<!--:-->/isU';

      if (preg_match_all($regex, $content, $matches)) {
        return array_combine($matches[1], $matches[2]);
      }

      return [];

    }
    
  }