<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $post;


 
      

?>

<style>
    
    div#main {
    color: transparent;
}
.container.membership-sinlge-post {
    color: black;
}
    .entry-content.wp-block-post-content.has-global-padding.is-layout-constrained {
    padding: 0;
}

    figure.alignwide.wp-block-post-featured-image {
    display: none;
}

h1.wp-block-post-title {
    display: none;
}

h1.wp-block-post-title.membership-sinlge-post-title {
    display: block;
    padding-bottom: 30px;
}
.membership-sinlge-post .slick-dots {
   position: relative;
    bottom: 41px;
    float: right;
    display: inline;
    width: auto;
    padding: 0;
    margin: 0;
    right: 26px;
    list-style: none;
    text-align: center;
}
.membership-sinlge-post  .slick-dots li button:before {
    font-family: slick;
    font-size: 7px;
        color: #fff;
}

.flex-container {
    padding: 0 !important;
}
    

</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container membership-sinlge-post">
    
    <?php
    
    
  $featured_img_url = get_the_post_thumbnail_url($post->ID,'full'); 
  
  
  $row1_editor_c = get_post_meta($post->ID, 'row1_editor_c', true); 
  
  
  $image_row1 = get_post_meta($post->ID, 'image_row1_ids', true); 
  
  
  $row2_editor_c =  get_post_meta($post->ID, 'row2_editor_c', true); 
  
  
  $video_row_2 = get_post_meta($post->ID, 'video_row_2_ids', true); 
  
  
  $row3_title = get_post_meta($post->ID, 'row3_title', true); 
  
  
  $video_row_3 =  get_post_meta($post->ID, 'video_row_3', true); 
  
  
   $content_gallery =  get_post_meta($post->ID, 'content_gallery', true); 
   
   $gallery_title = get_post_meta($post->ID, 'gallery_title', $single);
   
   
   $image_gallery =  get_post_meta($post->ID, 'image_gallery_ids', true); 
   
   
   $testimonails_content =  get_post_meta($post->ID, 'testimonails_content', true); 
   
   
   $auther_testimonails =  get_post_meta($post->ID, 'auther_testimonails', true); 
   
   $date_testimonails =  get_post_meta($post->ID, 'date_testimonails', true); 
   
   
   $testimonails_image =  get_post_meta($post->ID, 'testimonails_image_ids', true); 
   
    
    if(!empty($featured_img_url)):
    ?>
    <div class="feature-membership-image" style="width:100%; background: url('<?=$featured_img_url?>');     background-repeat: no-repeat;
    background-size: cover;
    background-position: center;">
        
        
        <div class='header-banner-content'>

            <h4 class='member-single-title'><?=$post->post_title?></h4>


            <div class='member-post-content'><?=$post->post_content?></div>
        
        </div>
     
    </div>
    <?php
        
    endif;
    ?>
    <!--
    
    Section 1
    
    -->
    
    <div class='section-container'>
    <?php
    
    if(!empty($row1_editor_c)):
        
        
        $columns = "";
        
        if(count($row1_editor_c) ==1):
            
            $columns = "col-12";
            
        endif;
        
         if(count($row1_editor_c) ==2):
            
            $columns = "col-lg-6 col-md-6 col-sm-12";
            
        endif;
        
         if(count($row1_editor_c) ==3):
            
            $columns = "col-lg-4 col-md-4 col-sm-12";
            
        endif;
    
    
    ?>
    <div class='row row1_editor_c'>
        
        <?php
        
        foreach($row1_editor_c as $key=>$single):
            
             $image_class ='without--image';
        
            if(!empty($image_row1[$key])):
                
                
                 $image_class ='';
                
            endif;
            
            ?>
        <div class='<?=$columns?> section--1'>
        
            <div class='single-item-container <?=$image_class?>'>
            <?php
            $image_class ='';
            
            if(!empty($image_row1[$key])):
                
                $image_src = wp_get_attachment_image_src($image_row1[$key], 'full');
                ?>
            
            <div class="section--1--image" style="width:100%; background: url('<?=$image_src[0]?>');
                  background-repeat: no-repeat;
    background-size: cover;
    background-position: center;"></div>
             <?php
                
            endif;
            ?>
            
            <div class='content--section1'><?=$single?></div>
        
            </div>
        </div>
        <?php
            
        endforeach;
        
        ?>
        
    </div>
    
    <?php
    endif;
    ?>
    
    <!-- Section 2 video and content-->
     <?php
    
    if(!empty($row2_editor_c)):
        
        
        $columns = "";
        
        if(count($row2_editor_c) ==1):
            
            $columns = "col-12";
            
        endif;
        
         if(count($row2_editor_c) ==2):
            
            $columns = "col-lg-6 col-md-6 col-sm-12";
            
        endif;
    
    
    ?>
    <div class='row row2_editor_c'>
        
        <?php
        
        foreach($row2_editor_c as $key=>$single):
            
            
            if($key == 0 ){
                
                $columns = "col-lg-8 col-md-8 col-sm-12";
            }
            else{
                
                $columns = "col-lg-4 col-md-4 col-sm-12";
            }
            
            ?>
        <div class='<?=$columns?> section--1'>
        
            <?php
            
            $video_url = get_post_meta($post->ID, 'video_row_2_url', true);
           
            if(!empty($video_url) && $key == 0):
                
                ?>
            
               <iframe width="640" height="360" src="https://www.youtube.com/embed/jgm58cbu0kw" title="Abstract Liquid Background Video (No Sound) — 4K UHD Abstract Liquid Screensaver" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            
            <?php
                
                
          
            else:
            if(!empty($video_row_2[0])):
                
                 $image_src = wp_get_attachment_image_src($video_row_2[0], 'full');
                
                ?>
            
                  <div class="section--1--image" style="width:100%; background: url('<?=$image_src[0]?>');
                  background-repeat: no-repeat;
    background-size: cover;
    background-position: center;"></div>
            
            <?php
                
                ?>
            
            
             <?php
                
            endif;
            
              endif;
            ?>
            
            <div class='content--section1'><?=$single?></div>
        
        </div>
        <?php
            
        endforeach;
        
        ?>
        
    </div>
    
    <?php
    endif;
    ?>
    
    
    
     <!-- Section 3-->
     <?php
    
    if(!empty($video_row_3)):
        
        
        $columns = "";
        
        if(count($video_row_3) ==1):
            
            $columns = "col-12";
            
        endif;
        
         if(count($video_row_3) ==2):
            
            $columns = "col-lg-6 col-md-6 col-sm-12";
            
        endif;
    
    
    ?>
    <div class='row row2_editor_c two-columns-content'>
        
        <?php
        
        if(!empty($row3_title)):
            
            ?>
        <div class='col-12 section3--title'>
            
            <h3><?=$row3_title?></h3>
            
        </div>
        <?php
            
        endif;
        foreach($video_row_3 as $key=>$single):
            
            
            ?>
        <div class='<?=$columns?> section--1'>
        
            
            <div class='content--section1'><?=$single?></div>
        
        </div>
        <?php
            
        endforeach;
        
        ?>
        
    </div>
    
    <?php
    endif;
    ?>
     
      
    
     
      <!-- Gallery section-->
    
       
    <?php
    
    if(!empty($image_gallery)):
        
       
        
        $gallery_title = !empty($gallery_title[0])?$gallery_title[0]:array();
    
    ?>
      
      
				
    <div class="flex-container">
        
        <ul class="slider-items">
        <?php
        
        $image_gallery =array_unique($image_gallery);
        
        foreach($image_gallery as $key=>$single_image):
            
              $image_src = wp_get_attachment_image_src($single_image, 'full');
                
            
            ?>
        
        
         <li class="slider-item">
		
             <img src="<?=$image_src[0]?>" alt="">
								
            <div class="slider-content">

                    <div class="flex-column">
                        
                            <?php 
                            
                            
                          if(!empty($gallery_title[$key])){
            
                                           echo "<h4>". $gallery_title[$key].'</h4>';
                               }
                                    
                            
                            if(!empty($content_gallery[$key])){
            
                                           echo "<p>". $content_gallery[$key].'</p>';
                               }
                               
                               ?>
                    </div>
            </div>
      </li>
            
             
        
      
        
        
            
             <?php
       
        endforeach;
        ?>
    </ul>
     
    </div>
      
   <?php
  endif;
   ?>
      
      <!--
    
    Testimonails section 
    
    -->
    <?php
    
    
    if(!empty($testimonails_content)):
        
        
        $columns = "";
        
        if(count($auther_testimonails) ==1):
            
            $columns = "col-12";
            
        endif;
        
         if(count($auther_testimonails) ==2):
            
            $columns = "col-lg-6 col-md-6 col-sm-12";
            
        endif;
        
         if(count($auther_testimonails) ==3):
            
            $columns = "col-lg-4 col-md-4 col-sm-12";
            
        endif;
    
    
    ?>
    <div class='row row1_editor_c'>
        
        <?php
        
        foreach($auther_testimonails as $key=>$single):
            
            
            ?>
        <div class='<?=$columns?> section--testimonails'>
            
            <div class='section--testimonail--inner'>
        
            <?php
            
           if($key==1):
               
          
            if(!empty($testimonails_image)):
                
                 $image_src = wp_get_attachment_image_src($testimonails_image, 'full'); 
                
                ?>
            
            
            
            <div class="section--1--image" style="width:100%; background: url('<?=$image_src[0]?>');
                  background-repeat: no-repeat;
    background-size: cover;
    background-position: center;"></div>
             <?php
                
            endif;
            
            endif;
            ?>
            
            
          <div class='testimonails-content-container'>
            
            <div class='content--testimonails'><?=!empty($testimonails_content[$key])?$testimonails_content[$key]:''?></div>
            
            <div class='content--testimonails-auther'><?=$single?></div>
            
            <div class='content--testimonails-date'><?=!empty($date_testimonails[$key])?$date_testimonails[$key]:'';?></div>
        
            </div>
                
            </div>
            
        </div>
        <?php
            
        endforeach;
        
        ?>
        
    </div>
    
    
    <?php
    
      endif;
      
      ?>
    <!-- Section 4 video and content-->
     <?php
    
   $row4_editor_c = get_post_meta($post->ID, 'row4_editor_c', true); 
  
   $video_row_4 = get_post_meta($post->ID, 'video_row_4_ids', true); 
  
   $video_row_url_4 = get_post_meta($post->ID, 'video_row_url_4', true);
    if(!empty($row4_editor_c)):
        
        
        
        $columns = "";
        
        if(count($row4_editor_c) ==1):
            
            $columns = "col-12";
            
        endif;
        
         if(count($row4_editor_c) ==2):
            
            $columns = "col-lg-6 col-md-6 col-sm-12";
            
        endif;
    
    
    ?>
    <div class='row row2_editor_c'>
        
        <?php
        
        foreach($row4_editor_c as $key=>$single):
            
            
            if($key == 0 ){
                
                
                $columns = "col-lg-4 col-md-4 col-sm-12";
            }
            else{
                
                $columns = "col-lg-8 col-md-8 col-sm-12";
            }
            
            ?>
        <div class='<?=$columns?> section--1'>
        
            <?php
            if(!empty($video_row_url_4)):
                
                if($key == 1):
                    
                    ?>
            
             <iframe width="640" height="360" src="https://www.youtube.com/embed/jgm58cbu0kw" title="Abstract Liquid Background Video (No Sound) — 4K UHD Abstract Liquid Screensaver" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
         
            <?php
                    
                    else:
                        
                        $image_ids  = get_post_meta($post->ID, 'section_4_image_ids', true);
                
                         $image_src = wp_get_attachment_image_src($image_ids, 'full'); 
                
                    ?>
            
                  <div class="section--1--image" style="width:100%; background: url('<?=$image_src[0]?>');
                  background-repeat: no-repeat;
    background-size: cover;
    background-position: center;"></div>
            
            <?php 
                endif;
                
                
                ?>
            
            
             <?php
                
            endif;
            ?>
            
            <div class='content--section1'><?=$single?></div>
        
        </div>
        <?php
            
        endforeach;
        
        ?>
        
    </div>
    
    <?php
    endif;
    
    ?>
    </div>
    <?php
      /*
       * 
       * 
       * Section Link 
       */
      
    $link_heading_title =  get_post_meta($post->ID, 'link_heading_title', true); 
   
   $link_title =  get_post_meta($post->ID, 'link_title', true); 
   
   
   $link_to =  get_post_meta($post->ID, 'link_to', true); 
    
    
   
   if(!empty($link_title)):
    ?>
    
    
    <div class='link-section'>
        
        
        <?php
        
        if(!empty($link_heading_title)):
            
            echo  "<h3 class='link-main-title'>$link_heading_title</h3>";
            
        endif;
        
        foreach($link_title as $key=>$single):
            
            ?>
        
        <div class='links-item'>
        <a href="<?=$link_to[$key]?>" target="_blank"><?=$single?></a>
        </div>
        <?php
            
        endforeach;
        ?>
        
        
        
        
    </div>
    
    <?php
    
    endif;
   
    
     /*
       * 
       * 
       * last Section
       */
      
  $last_section_content =  get_post_meta($post->ID, 'last_section_content', true); 
   
   $last_section_image=  get_post_meta($post->ID, 'last_section_image', true); 
   
   
    
    
   
   if(!empty($last_section_image)):
    ?>
    
    
    <div class='last-section'>
        
        <div class='last-section-inner'>
            
            <div class="last-seciton-image"  <div class="feature-membership-image" style="width:100%; background: url('<?=$last_section_image?>');     background-repeat: no-repeat;
    background-size: cover;
    background-position: center;">
                
                <?= !empty($last_section_content)?$last_section_content:''?>
                
            </div>
            
        </div>
        
        
    </div>
    
    <?php
    
    endif;
    ?>
      
      
    
</div>


<script type="text/javascript">

	
        
        
$('.slider-items').slick({
        dots: true,
  infinite: true,
  autoplay:false,
  speed: 300,
  slidesToShow: 1,
		  responsive: [
    {
          breakpoint: 680,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
 
  ]
      });

        

</script>
