<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$current_user = wp_get_current_user();

$post_id = $_POST['post_id'];

$post_name = '';

$content_post ='';

$ID ='';

if(!empty($post_id)):
    
    $args = array(
    "ID"=>$_POST['post_id'],
    'orderby' => 'post_date',
    'post_status' => array('publish', 'draft'),
    'post_type' => 'membership-posts',
);

$post = get_posts($args);

$post_name = !empty($post[0]->post_title)?$post[0]->post_title:'';

$content_post = !empty($post[0]->post_content)?$post[0]->post_content:'';

$ID = !empty($post[0]->ID)?$post[0]->ID:'';
    
    
endif;





?>


<!-- Initialize the editor. -->


  <form method="post" id="user-post-form" enctype="multipart/form-data">
      
      <input name="user_id" value="<?=$current_user->ID?>" type="hidden">
      
      
       <input name="membership_post_id" value="<?=$ID?>" type="hidden">
        <div class="form-group row">
            
        <label for="postname" class="col-12 col-form-label post-label">Post name</label>

        <div class="col-sm-10">

            <input type="text" value="<?=$post_name?>" name="post_name" class="form-control" id="post_name" required="">

        </div>
        
        <div class="col-sm-10">
            
             <label for="postname" class="col-12 col-form-label post-label">Post Body</label>

             <textarea name="post_body" id="post_body" required=""><?=$content_post?></textarea>
        
        </div>
        
        <?php
        
        if($_POST['number_of_images']>0):
            
            
            $gallery_fields = get_post_meta($_POST['post_id'], 'gallery_membership', true);
            
            

        

if(!empty($gallery_fields)):
    
    



?>

<style>
    
    .attachment-c-logos {
    display: inline-block;
}
</style>

  <div class='c-logo-container'>
                   
                 <?php
                   
                 
                 foreach($gallery_fields as $single):
                    
                    
                   ?>
                   
                 <div class="attachment-c-logos">
                    
                    
                  <a href="<?php echo wp_get_attachment_url( $single ) ?>" target="_blank">
                     
                      <img src="<?php echo wp_get_attachment_url( $single ) ?>" width="50" height="50">
                      
                     
                  </a>
                      
                       
                </div>
      <?php
                  endforeach;
      ?>
                   
                 
               </div>

<?php
endif;

?>
        <div class="col-sm-10">
            
             <label for="postname" class="col-12 col-form-label post-label">Post images(maximum <?=$_POST['number_of_images']?> images)</label>

            <input type="file"  <?=(!empty($ID))?"":'required' ?> name="post_images[]" multiple accept="image/png, image/gif, image/jpeg" />
           
        </div>
        
        <?php
        endif;
        ?>
        
        
        
       
        
  </div>
        
    <div class="col-12">
      
        <button class="btn btn-primary" type="submit">Create</button>
 
    </div>
        
</form>
