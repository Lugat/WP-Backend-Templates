<?
  
  /**
   * Frontend
   * 
   * @package Jinx
   * @copyright Copyright (c) 2018 SquareFlower Websolutions
   * @license BSD License
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.2
   * @since 0.1
   */
  
  namespace Jinx;
  
  abstract class Frontend extends Plugin
  {
    
    /**
     * Init
     */
    public static function init()
    {
      // do nothing
    }

    /**
     * Get the blocks content
     * 
     * @global WP_Post $post
     * @param string $block
     * @return string
     */
    public static function getContentBlock($block)
    {
    
      global $post;

      // Parse the page blocks from the post content
      $blocks = self::parseBlocks($post->post_content);

      // Check if the block exists and return its content
      if (isset($block) && array_key_exists($block, $blocks)) {
        return apply_filters('the_content', $blocks[$block]);
      }
      
      // Otherwise return null
      return null;

    }
    
  }