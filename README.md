# WP-Backend-Templates
Allows you to create simple backend templates for WordPress.

## How it works

This plugins works only when using custom page templates. Each template needs a YAML-file which contains the backend template.

_/my-theme/my-page-template.php --> /my-theme/backend/my-page-template.yaml_

All content blocks will be saved in the posts content field, there is no meta data. The blocks are seperated via HTML comments, so even if you delete the plugin, the content will stay the same.

Each row can contain a variable number of cols.
```
-
  topLeft:
    label: Content Top Left
    desc: Content on the upper left side of the page
    width: 1/2
    
  topRight:
    label: Content Top Right
    desc: Content on the upper right side of the pagee
    width: 1/2
    
-
  bottomLeft:
    label: Content Bottom Right
    width: 1/3
    settings:
      media_buttons: false
      teeny: true
      
  bottomCenter:
    label: Content Bottom Center
    width: 1/3
    settings:
       media_buttons: false
       teeny: true
       
  bottomRight:
    label: Content Bottom Right
    width: 1/3
    settings:
      media_buttons: false
      teeny: true
```

## Options

At the moment your cols key must contain only letters.

```
label: optional, if not set the key will be used
desc: optiional informations
width: optional, default is 1 which will result in 100% width
settings: optional settings for the wp_editor
```

## Frontend

To get the content of a block, you need to call the function "the_content_block" with the key of the specific block

```php
while (have_posts()) : the_post();
  
  the_content_block('topLeft');
  
  the_content_block('topRight');
  
  the_content_block('bottomLeft');
  
  the_content_block('bottomCenter');
  
  the_content_block('bottomRight');
  
endwhile;
```
