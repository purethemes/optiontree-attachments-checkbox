# OptionTree Attachments Checkbox #

Extension for OptionTree WordPress plugin. Displays list of attached images to post if used in meta-boxes.php in theme mode. ( it's also possible to use in Settings Mode but doesn't make any sense ;) )


## How to use it ##


You need to use OptionTree in "theme mode" and with meta boxes. Just add to your meta-boxes.php

```php
 $meta_boxes = array(
    'id'        => 'metabox_slider',
    'title'     => 'Slider settings',
    'desc'      => '',
    'pages'     => array( 'post' ),
    'context'   => 'normal',
    'priority'  => 'high',
    'fields'    => array(
  		array(
	        'label' => 'Exlude photos from slider',
	        'id' => 'exluded_photos',
	        'type' => 'attachments-checkbox',
	        'desc' => 'By default all photos you have uploaded to post will be displayed in slider, here you can choose which one you want to exclude, simply by just clicking them (blue border should appear).',
	        'post_type' => 'post',
	 
	      )
    );

  ot_register_meta_box( $meta_boxes );    
```

```php
$excluded = get_post_meta($post->ID, 'exluded_photos', true);
```
returns array with ID of selected images.



![alt text](http://i.imgur.com/AU9gR.png "Example implementation")