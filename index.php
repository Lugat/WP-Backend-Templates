<?

  /**
   * Plugin Name: Backend Templates
   * Plugin URI: http://squareflower.de
   * Description: Allows you to create simple backend templates for WordPress
   * Version: 0.2.1
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   */
  
  require_once('src/Plugin.php');
  
  if (is_admin()) {
    
    require_once('src/Backend.php');
    Jinx\Backend::init();
    
  } else {
    
    require_once('src/Frontend.php');
    Jinx\Frontend::init();
    
    /**
     * Helper function
     * 
     * @param string $block
     */
    function the_content_block($block) {
      echo Jinx\Frontend::getContentBlock($block);
    }
    
  }