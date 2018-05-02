<?
  
  /**
   * Backend
   * 
   * @package Jinx
   * @copyright Copyright (c) 2018 SquareFlower Websolutions
   * @license BSD License
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.2
   * @since 0.1
   */
  
  namespace Jinx;
  
  use \Alchemy\Component\Yaml\Yaml as Yaml;
  
  abstract class Backend extends Plugin
  {
    
    const PREFIX = '_backend-templates-content-blocks';
    
    /**
     * The backend template
     * 
     * @var array
     */
    protected static $template;
    
    /**
     * Init
     */
    public static function init()
    {
      
      // Load Yamp parser
      require_once(__DIR__.'/vendor/Yaml.php');
      
      // Add assets to the WordPress backend
      add_action('admin_enqueue_scripts', function() {
        
        $assetsPath = plugins_url('/../assets', __FILE__);

        wp_enqueue_style('backend-template-css', $assetsPath.'/css/style.css');
        wp_enqueue_script('backend-template-js', $assetsPath.'/js/main.js');

      }, 1);
      
      // Add action to "edit_form_after_title"
      add_action('edit_form_after_title', 'Jinx\Backend::before', 9999);

      // Add action to "content_save_pre"
      add_filter('content_save_pre', function($content) {
        
        if (isset($_POST[self::PREFIX]) && !empty($_POST[self::PREFIX])) {

          // Start empty content
          $content = '';

          // Get the content fields and implode them with HTML-comments
          // The content will still be shown, even if the plugin is removed
          foreach ($_POST[self::PREFIX] as $block => $string) {
            $content .= '<!--:'.$block.'-->'.trim($string).'<!--:-->'; 
          }
        
        }

        return $content;

      }, 10, 1);
      
    }
    
    /**
     * Will be rendered before the editor is printed
     * 
     * @param WP_Post $post
     */
    public static function before($post)
    {
      
      // Get the template
      self::$template = self::getTemplate($post->ID);
      
      // If a template exists, the default editor will be hidden and the content blocks will be printed
      if (isset(self::$template)) {
        
        // Add action to "edit_form_after_editor"
        add_action('edit_form_after_editor', 'Jinx\Backend::after', 1);
        
        // Open wrapper
        echo '<div id="backend-template">';
        
      }
      
    }
    
    /**
     * Renders the content blocks
     * 
     * @param WP_Post $post
     */
    public static function after($post)
    {
      
      // Parse the page blocks from the post content
      $blocks = self::parseBlocks($post->post_content);

      // Loop throught the backend templates rows
      foreach (self::$template as $row) {

        // Open row
        echo '<div class="row">';

        // Loop throught the rows cols
        foreach ($row as $block => $col) {
          
          // Get the cols content
          $content = isset($blocks[$block]) ? $blocks[$block] : null;
          
          // Col
          self::buildCol($block, self::getCol($block, $col), $content);

        }

        // Close row
        echo '</div>';

      }

      // Close wrapper
      echo '</div>';
      
    }
    
    /**
     * Build the HTML for the col
     * 
     * @param string $block
     * @param array $col
     */
    protected static function buildCol($block, $col, $content)
    {
      
      // Create a col with the given with
      echo '<div class="col" style="width:'.self::getColWidth($col['width']).'%">';
        echo '<div class="postbox">';

          // Create the cols laben and key
          echo '<h2>'.$col['label'].' <span>'.$block.'</span></h2>';

          echo '<div class="inside">';

          // Create the editor for the cols content block
          // The editor id will be validated
          // @see https://codex.wordpress.org/Function_Reference/wp_editor
          wp_editor($content, self::getEditorId($block), array_merge($col['settings'], [
            'textarea_name' => self::PREFIX.'['.$block.']' // the name of the textarea can't be changed
          ]));

          // Create the description if set
          if (!empty($col['desc'])) {
            echo '<p>'.$col['desc'].'</p>';
          }

          echo '</div>';

        echo '</div>';
      echo '</div>';

    }
    
    /**
     * Validate the cols configuration
     * 
     * @param string $block
     * @param array $col
     * @return array
     */
    protected static function getCol($block, $col)
    {
      
      return array_merge([
        'label' => $block, // default label is the blocks key
        'desc' => null, // description is optional
        'width' => 1, // default with is 100%
        'settings' => [] // settings for the wp_editor are optional
      ], $col);
      
    }
    
    /**
     * Creates a valid ID for the editor by allowing only lowercase letters
     * 
     * @param string $block
     * @return string
     */
    protected static function getEditorId($block)
    {
      return 'content'.preg_replace('/[^a-z]/', '', strtolower(self::PREFIX.$block));
    }
    
    /**
     * Calculates the cols width and returns a percentage value
     * 
     * @param string $width
     * @return float
     */
    protected static function getColWidth($width)
    {
      return @eval("return $width*100;");
    }
    
    /**
     * Loads the backend template based on the page template
     * 
     * @param int $pid
     * @return array
     */
    protected static function getTemplate($pid)
    {

      // Get the template name of the page
      $template = str_replace('.php', '', get_post_meta($pid, '_wp_page_template', true));
      
      // If no template is used, the normale editor will be shown
      if (!empty($template)) {

        // Get the path of the backend template file
        $basePath = apply_filters('backend_templates_base_path', get_stylesheet_directory().'/backend');
        
        $path = rtrim($basePath, '/').'/'.$template.'.yaml';

        // If the backend template file exists, parse it and return the content
        if (file_exists($path)) {
          return (new Yaml)->load($path);
        }
      
      }

      // Otherwise return null
      return null;

    }
    
  }