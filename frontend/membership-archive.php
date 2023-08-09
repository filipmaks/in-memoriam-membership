<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();



$args = array(
    'orderby' => 'post_date',
    'order' => 'DESC',
    'author' => $current_user->ID,
    'post_status' => array('publish', 'draft'),
    'post_type' => 'membership-posts',
);

$all_post = new WP_Query($args);



?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<div class="container membership-post-type">
    
    
    <?php
    
    if(!empty($all_post->posts)):
        
        foreach($all_post->posts as  $key=>$single):
   
    ?>
    <div class="row post-member-ship-rows">
        
        
        <?php
        $featured_img_url = get_the_post_thumbnail_url($single->ID,'medium'); 
         
        if(!empty($featured_img_url)):
        ?>
        
      
            
        <div class="col-lg-3 col-md-2 col-sm-12">
            
            <a href="<?= get_permalink($single->ID)?>">
                
            <img src="<?=$featured_img_url?>" class="membership-feature-image" alt="<?=$single->post_title;?>">
            
             </a>
             
        </div>
        
       
        
        
        <?php
        endif;
        ?>
        
        <div class="col-lg-9 col-md-9 col-sm-12">
           
            <a href="<?= get_permalink($single->ID)?>">
                
                
            <h2 class="membership-title"><?=$single->post_title;?></h2>
            
            <div class="membership-content"><?=$single->post_content;?></div>
            
            </a>
            
        </div>
        
        
        
    </div>
        
    <?php
    
    endforeach;
    
    endif;
    ?>
    
</div>

<?php

get_footer();
