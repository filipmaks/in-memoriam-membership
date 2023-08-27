<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $post;

 
function get_colmuns($column_size){
    
    $classes = '';
    switch($column_size):
        
        case '20%':
            
       $classes = "col-lg-2 col-md-2 col-sm-12 custom-column-class member-post-six-columm";
            
        break;
        
         case '30%':
            
        $classes = "col-lg-3 col-md-3 col-sm-12 custom-column-class member-post-four-columm";
             
        break;
    
     case '40%':
         
       $classes = "col-lg-4 col-md-4 col-sm-12 custom-column-class member-post-three-columm";     
        
        break;
    
     case '50%':
            
         $classes = "col-lg-6 col-md-6 col-sm-12 custom-column-class member-post-two-columm" ;
        
        break;
    
     case '60%':
            
         $classes = "col-lg-8 col-md-8 col-sm-12 custom-column-class member-post-two-columm";
        
        break;
    
     case '80%':
            
         $classes = "col-lg-10 col-md-10 col-sm-12 custom-column-class member-post-two-columm";
        
        break;
    
     case '100%':
            
        $classes = "col-12 custom-column-class";
         
        break;
        
        
    endswitch;
    
    
    return $classes;
}
      

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

<div class="wrapper frontend-wrapper">
<?php

    if( $post->post_status=='publish'):
?>
    <div class="membership-sinlge-post frontend">
    
    <?php
    
    
  $featured_img_url = get_the_post_thumbnail_url($post->ID,'full'); 
  
  $dob = get_post_meta($post->ID, 'member_dob', true);
  
  $dod = get_post_meta($post->ID, 'member_date_of_death', true);
  
    if(!empty($featured_img_url)):
    ?>
    <div class="hero feature-membership-image has_bgr" style="background-image: url('<?=$featured_img_url?>');">
        
        
        <div class='holder left'>

            <article>
                                
                <h1 class='animated anim_y in_view'><?=$post->post_title?></h1>

                <div class='anim_y in_view member-post-content'><?=$post->post_content?></div>
            </article>

            <?php
        
        if(!empty($dob) || !empty($dod)):
            
            ?>
            
             <div class='member-post-content'>
            
                 <?php
                  if(!empty($dob)):
                 ?>
                 <span class="dob"><?=$dob?></span>
             <?php
            endif;
            
            echo "-";
            
            if(!empty($dod)):
             ?>
             
                 <span class="dob"><?=$dod?> </span>
            <?php
            endif;
            
            ?>
                 </div>
                 <?php
            
            
             endif;
             
            ?>
        
        </div>
     
    </div>
    <?php
        
    endif;
    
    ?>
    
</div>

<div class="other-fields-container">
    <?php
    
    
           $ID =  $post->ID;
           
           $number_rows = get_post_meta($ID, 'rows_number', true);


        $number_of_columns = get_post_meta($ID, 'column_number', true);

        $member_ship_title = get_post_meta($ID, 'member_ship_title', true);

        $element_type = get_post_meta($ID, 'element_type', true);


        $member_ship_sub_title = get_post_meta($ID, 'member_ship_sub_title', true);

        $column_class_row = get_post_meta($ID, 'column_class_row', true);
        
        $rows_number = get_post_meta($ID, 'rows_number', true);
        
        $column_number = get_post_meta($ID, 'column_number', true);

        $member_ship_image_id = get_post_meta($ID, 'member_ship_image_id', true);
        
        $member_dob = get_post_meta($ID, 'member_dob', true);
        
        $member_date_of_death = get_post_meta($ID, 'member_date_of_death', true);
        
        $editor = get_post_meta($ID, 'editor', true);
        
        $title_sub_title_positioin = get_post_meta($ID, 'title_sub_title_positioin', true);
        
        $only_title= get_post_meta($ID, 'only_title', true);
        
         $column_size = get_post_meta($ID, 'column_size', true);
         
       
    ?>
    <!--
    
    Section 1
    
    -->
     <?php
                if (!empty($number_rows)) {

                    for ($i = 0; $i <= $number_rows[count($number_rows) - 1]; $i++) {

                        $columns = $number_of_columns[$i];

                        $columns_classes = '';

                        switch ($columns) {

                            case 1:

                                $columns_classes = "custom-column-class member-post-single-columm";

                                break;

                            case 2:

                                $columns_classes = "custom-column-class member-post-two-columm";

                                break;

                            case 3:

                                $columns_classes = "custom-column-class member-post-three-columm";

                                break;
                        }
                        
                        
                            
                            
                        ?>

                        <div class="row row-holder <?=$column_class_row[$i]?>">


                            <?php
                            if ($number_of_columns[$i] > 0):

                                for ($c = 0; $c < $number_of_columns[$i]; $c++) {

                                    // print_r($element_type);
                                    $columns_classes = $columns_classes;
                                    
                                    
                                    if(!empty($column_size[$i][$c])):
                                        
                                      $columns_classes =   get_colmuns($column_size[$i][$c]);
                                    
                                    endif;
                                  
                                    ?>
                            

                                    <div class='card <?= $columns_classes ?>'>


                                            <?php
                                           $type = trim($element_type[$i][$c]);

                                            switch ($type) {

                                                case "editor_with_title_subtitle":
                                                    
                                                    ?>
                                        
                                                 
                                                       <div class="inner-item-container editor-with-title-subtitle">
                                                           
                                                           <div class="content">
                                                               
                                                            <?=$editor[$i][$c]?>
                                                               
                                                           </div>
                                                           
                                                           <div class="title-subtitle">
                                                                
                                                               <h2 class="main-title"><?=$member_ship_title[$i][$c]?></h2>
                                                           
                                                               <h2 class="sub-title"><?=$member_ship_sub_title[$i][$c]?></h2>
                                                           
                                                           </div>
                                                           
                                                       </div>
                                                    <?php
                                                    break;
                                                
                                                 case "only_title":
                                                    
                                                    ?>
                                        
                                        
                                                       <div class="only-title inner-item-container">
                                                           
                                                           <div class="content">
                                                               
                                                            <?=$only_title[$i][$c]?>
                                                               
                                                           </div>
                                                           
                                                       </div>

                                                    <?php
                                                    break;

                                                case "full_editor":
                                                    
                                                    ?>
                                        
                                        
                                                       <div class="full-editor inner-item-container">
                                                           
                                                           <div class="content">
                                                               
                                                            <?=$editor[$i][$c]?>
                                                               
                                                           </div>
                                                           
                                                       </div>

                                                    <?php
                                                    break;

                                                case "video_with_title_subtitle":
                                                    ?>
                                                         <div class="inner-item-container video-with-title-subtitle">
                                                           
                                                           <?php
                                                                    
                                                                    $attachment_metadata =wp_get_attachment_url($member_ship_image_id[$i][$c]);
                                                                    
                                                                    if(!empty($attachment_metadata)):
                                                                    ?>
                                                                    <video controls>
                                                                    
                                                                        <source src='<?=$attachment_metadata?>' type='video/mp4'>
                                                                    
                                                                        Your browser does not support the video tag.
                                                                 
                                                                    </video> 
                                                             <?php
                                                                  endif;
                                                             ?>
                                                           
                                                           <div class="title-subtitle">
                                                                
                                                               <h2 class="main-title"><?=$member_ship_title[$i][$c]?></h2>
                                                           
                                                               <h2 class="sub-title"><?=$member_ship_sub_title[$i][$c]?></h2>
                                                           
                                                           </div>
                                                           
                                                       </div>
                                                    <?php
                                                    
                                                    break;

                                                case "image_with_title_subtitle":
                                                    ?>
                                        <div class="inner-item-container image-with-title-subtitle">
                                                           <?php
                                                             
                                                           $image_attributes = wp_get_attachment_image_src($member_ship_image_id[$i][$c], 'full');
                    
                                                           ?>
                                            <div class="image-container has_bgr" style="<?=!empty($image_attributes)?'background-image:url('.$image_attributes[0].');':''?>; ">  
                                                               
                                                           </div>
                                                           
                                                           <div class="title-subtitle">
                                                                
                                                               <h2 class="main-title"><?=$member_ship_title[$i][$c]?></h2>
                                                           
                                                               <h2 class="sub-title"><?=$member_ship_sub_title[$i][$c]?></h2>
                                                           
                                                           </div>
                                                           
                                                       </div>

                            <?php
                            break;
                    }
                    ?>


                                    </div>

                    <?php
                }

            endif;
            ?>
                        

                        </div>
                            <?php
                        }
                    }
                    ?>
    
</div>
<?php
else:
    
          echo "<p class='admin-post'> Sorry your post is approved by admin!</p>";
endif;
?>
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


</div>