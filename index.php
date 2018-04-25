<?

  /**
   * Plugin Name: Backend Templates
   * Plugin URI: http://squareflower.de
   * Description: Allows you to create simple backend templates for Wordpress
   * Version: 0.1
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
    
    function the_content_block($block) {

      $contentBlock = Jinx\Frontend::getContentBlock($block);

      echo apply_filters('the_content', $contentBlock);

    }
    
  }