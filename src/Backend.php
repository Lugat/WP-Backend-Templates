<?
  
  namespace Jinx;
  
  use \Alchemy\Component\Yaml\Yaml as Yaml;
  
  abstract class Backend extends Plugin
  {
            
    public static function init()
    {
      
      require_once(__DIR__.'/vendor/Yaml.php');
      
      add_action('admin_enqueue_scripts', function() {
        
        $assetsPath = plugins_url('/../assets', __FILE__);

        wp_enqueue_style('backend-template-css', $assetsPath.'/css/style.css');
        wp_enqueue_script('backend-template-js', $assetsPath.'/js/main.js');

      }, 1);
      
      add_action('edit_form_after_title', 'Jinx\Backend::before', 9999);

      add_filter('content_save_pre', function($content) {

        $content = '';

        foreach ($_POST['content'] as $block => $blockContent) {
          $content .= '<!--:'.$block.'-->'.$blockContent.'<!--:-->'; 
        }

        return $content;

      }, 10, 1);
      
    }
    
    public static function before($post)
    {
            
      $template = self::getTemplate($post->ID);
      
      if (isset($template)) {
                        
        add_action('edit_form_after_editor', 'Jinx\Backend::after', 1);
        
        echo '<div id="backend-template">';
        
      }
      
    }
    
    public static function after($post)
    {
      
      $template = self::getTemplate($post->ID);

      $blocks = self::parseBlocks($post->post_content);

      foreach ($template as $row) {

        echo '<div class="row">';

        foreach ($row as $block => $col) {
          
          $content = isset($blocks[$block]) ? $blocks[$block] : null;
          $settings = isset($col['settings']) ? $col['settings'] : [];

          echo '<div class="col" style="width:'.self::getColWidth($col['width']).'%">';
            echo '<div class="postbox">';
              
              echo '<h2>'.$col['label'].'</h2>';
              echo '<div class="inside">';

              wp_editor($content, 'content'.strtolower($block), array_merge($settings, [
                'textarea_name' => 'content['.$block.']',
              ]));

              echo '</div>';

            echo '</div>';
          echo '</div>';

        }

        echo '</div>';

      }

      echo '</div>';
      
    }
    
    protected static function getColWidth($width)
    {
      return eval("return {$width}*100;");
    }
    
    protected static function getTemplate($pid)
    {

      $template = str_replace('.php', '.yaml', get_post_meta($pid, '_wp_page_template', true));
      
      if (!empty($template)) {

        $path = get_stylesheet_directory().'/backend/'.$template;

        if (file_exists($path)) {
          return (new Yaml)->load($path);
        }
      
      }

      return null;

    }
    
  }