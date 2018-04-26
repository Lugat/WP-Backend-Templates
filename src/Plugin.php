<?
  
  /**
   * Plugin
   * 
   * @package Jinx
   * @copyright Copyright (c) 2018 SquareFlower Websolutions
   * @license BSD License
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1
   * @since 0.1
   */
  
  namespace Jinx;
  
  abstract class Plugin
  {
    
    /**
     * arse the page blocks from a string
     * 
     * @param string $string
     * @return array
     */
    public static function parseBlocks($string)
    {

      // Create the regular expression
      $regex = '/<!--:([a-z]{3,})-->(.*)<!--:-->/isU';

      // Check if page blocks exist and return them
      if (preg_match_all($regex, $string, $matches)) {
        return array_combine($matches[1], $matches[2]);
      }

      // Otherwise return an empty array
      return [];

    }
    
  }