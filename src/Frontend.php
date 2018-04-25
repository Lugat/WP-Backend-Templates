<?
  
  namespace Jinx;
  
  abstract class Frontend extends Plugin
  {
    
    public static function init()
    {
      
      
      
    }

    public static function getContentBlock($block = null)
    {
    
      global $post;

      $blocks = self::parseBlocks($post->post_content);

      if (isset($block) && array_key_exists($block, $blocks)) {
        return $blocks[$block];
      }
      
      return null;

    }
    
  }