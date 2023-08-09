<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$current_user = wp_get_current_user();


$args = array(
    'orderby' => 'post_date',
    'order' => 'DESC',
    'author' => $current_user->ID,
    'post_status' => array('publish', 'draft'),
    'post_type' => 'membership-posts',
);

$all_post = new WP_Query($args);


$user_level = get_user_meta($current_user->ID, 'member_level', true);

$membership_name = get_option('membership_name');

$membership_number_posts = get_option('membership_number_posts');

$membership_number_block_posts =  get_option('membership_number_block_posts');

$membership_costs = get_option('membership_costs');

$memebership_status = get_option('memebership_status');

$number_of_post = 0;

$number_of_blocks = 0;

if (!empty($membership_name)):

    foreach ($membership_name as $key => $single):


        if ($single == $user_level):

            $number_of_post = !empty($membership_number_posts[$key]) ? $membership_number_posts[$key] : 0;

            $number_of_blocks= !empty($membership_number_block_posts[$key]) ? $membership_number_block_posts[$key] : 0;

        endif;

    endforeach;

endif;

if (!empty($_POST)):



    //  print_r($_POST);

    if (!empty($_POST['post_name'])):


        if (!empty($_POST['membership_post_id'])):

           
            $post_id = wp_update_post(
                    array(
                        'ID' => $_POST['membership_post_id'],
                        'comment_status' => 'close',
                        'ping_status' => 'close',
                        'post_author' => $_POST['user_id'],
                        'post_title' => $_POST['post_name'],
                        'post_name' => sanitize_title($_POST['post_name']),
                        'post_status' => 'draft',
                        'post_content' => $_POST['post_body'],
                        'post_type' => 'membership-posts',
                    )
            );

            $user_name = $current_user->data->display_name;

            $post_id = $_POST['membership_post_id'];

            $post_id = $post_id; // Replace with the ID of the post you want to delete postmeta for

            // Get all meta keys associated with the post
            $meta_keys = get_post_custom_keys($post_id);

            if ($meta_keys) {
              foreach ($meta_keys as $meta_key) {
                // Delete the postmeta for each key
                delete_post_meta_by_key($meta_key, $post_id);
              }
            }
          
            $post_title = $_POST['post_name'];

            $admin_url = admin_url() . "post.php?post=$post_id&action=edit";

            $html = "<h2>Hello Dear</h2>,";

            $html .= "<p> User $user_name  has updated the post $post_title.  <a href='$admin_url'><strong>Click here</strong> </a>to approve it. OR copy the following link! to approve</p>";

            $html .= "<p>$admin_url</p>";

            $html .= "<p><strong>Thanks,</strong></p>";

            $to = get_option('admin_email');

            $subject = 'Update Post';

            $body = $html;

                                         $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);

            wp_mail($to, $subject, $body, $headers);

        else:

            $post_id = wp_insert_post(
                    array(
                        'comment_status' => 'close',
                        'ping_status' => 'close',
                        'post_author' => $_POST['user_id'],
                        'post_title' => $_POST['post_name'],
                        'post_name' => sanitize_title($_POST['post_name']),
                        'post_status' => 'draft',
                        'post_content' => $_POST['post_body'],
                        'post_type' => 'membership-posts',
                    )
            );

            $user_name = $current_user->data->display_name;

            $post_title = $_POST['post_name'];

            $admin_url = admin_url() . "post.php?post=$post_id&action=edit";

            $html = "<h2>Hello Dear</h2>";

            $html .= "<p> User $user_name  created post $post_title.  <a href='$admin_url'><strong>Click here</strong></a>  to approve it. OR copy the following link!to approve</p>";

            $html .= "<p>$admin_url</p>";

            $html .= "<p><strong>Thanks,</strong></p>";

            $to = get_option('admin_email');

            $subject = 'New Post';

            $body = $html;

                                          $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);

            wp_mail($to, $subject, $body, $headers);



        endif;

       
        foreach ($_POST as $key => $single):

            if (!empty($single) && $key != "post_name" && $key != 'post_body' && $key != "user_id"):

                update_post_meta($post_id, $key, $single);

            endif;



        endforeach;

    endif;


    if (!empty($_FILES['feature_image']['tmp_name'])):



        $upload_dir = wp_upload_dir(); // Get the upload directory


        $image_name = 'post-' . get_current_user_id() . '_' . time() . '_' . $_FILES['feature_image']['name']; // Set the name of your image file

        $target_file = $upload_dir['path'] . '/' . $image_name; // Set the target path for your image file
// Check if the file already exists in the upload directory
        if (file_exists($target_file)) {

            echo "Sorry, file already exists.";
        } else {

            $image_data = file_get_contents($_FILES['feature_image']['tmp_name']); // Get the image data from the uploaded file

            $uploaded_file = wp_upload_bits($image_name, null, $image_data, date('Y/m')); // Upload the file to the specified directory
            // If wp_upload_bits() returned an error, display it to the user


            if (!empty($uploaded_file['error'])) {
                
            } else {

                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // The file has been uploaded successfully, so insert it into the media library
                $attachment = array(
                    'guid' => $uploaded_file['url'],
                    'post_mime_type' => $uploaded_file['type'],
                    'post_title' => sanitize_file_name($image_name),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file']);

                // Update the attachment metadata
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);

                wp_update_attachment_metadata($attachment_id, $attachment_data);

                set_post_thumbnail($post_id, $attachment_id);
            }
        }
    // print_r($single_file);





    endif;

    $_SESSION['message_create_post'] = "Your post is created, wait untill admin approve it!";


    $url = site_url() . '/user-profile';

echo "<script>window.location.href='$url'</script>";

endif;
?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!--<link rel="stylesheet" href="/assets/member-ship.css"> -->

<link rel="stylesheet" href="<?php echo plugins_url().'/user-membership'?>/assets/member-ship.css" />
<script>
    
   
</script>
<style>

    form#user-post-form {
    width: 100%;
}
    .modal form {
    width: 100%;
}
    .loading_holder {
    display: none;
}
    iframe {
        width: 200px;
        height: 135px;
        display: block;
        margin: 10px;
    }
.form-control.error[name="elements"] {
  border: 1px solid red;
}

</style>
<div class="container user-membership-postcreate">
    <?php
    if ($all_post->post_count < $number_of_post || !empty($_GET['id'])):

        $current_user = wp_get_current_user();

        $post_id = $_POST['post_id'];

        $post_name = '';

        $content_post = '';

        $ID = '';

        if (!empty($_GET['id'])):


            $ID = $_GET['id'];


        endif;






        $post = array();

        if (!empty($_GET['id'])):

            $post = get_post($_GET['id']);

        endif;

        $number_rows = get_post_meta($ID, 'rows_number', true);


        $number_of_columns = get_post_meta($ID, 'column_number', true);

        $column_class_row = get_post_meta($ID, 'column_class_row', true);

        $member_ship_title = get_post_meta($ID, 'member_ship_title', true);

        $element_type = get_post_meta($ID, 'element_type', true);


       // echo "<pre/>";
        
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


        <!-- Initialize the editor.  -->


        <form method="post" id="user-post-form" enctype="multipart/form-data">

            <input name="user_id" value="<?= $current_user->ID ?>" type="hidden">



            <input name="membership_post_id" value="<?= $ID ?>" type="hidden">

            <div class="form-group row">

                <label for="postname" class="col-12 col-form-label post-label">Post name</label>

                <div class="col-sm-10">

                    <input type="text" value="<?= !empty($post->post_title) ? $post->post_title : '' ?>" name="post_name" class="form-control" id="post_name" required="">

                </div>

                <div class="col-sm-10">

                    <label for="postname" class="col-12 col-form-label post-label">Post Body</label>

                    <textarea id="post_body" name="post_body"  rows="6"><?= !empty($post->post_content) ? $post->post_content : '' ?></textarea>
                    <script>

                    </script>

                </div>

                <div class="col-sm-10">

                    <label for="postname" class="col-12 col-form-label post-label">Feature Image</label>

                    <input type="file" name="feature_image" accept="image/png, image/gif, image/jpeg">

                    <?php
                    if (!empty($_GET['id'])):


                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($_GET['id']), 'medium');

                        if (!empty($image)):
                            ?>

                            <img src="<?php echo $image[0]; ?>">

                            <?php
                        endif;
                    endif;
                    ?>

                </div>

                <div class="col-sm-10">

                    <label for="postname" class="col-12 col-form-label post-label">Date of Birth</label>

                    <input type="text" name="member_dob" data-date-format="yyyy-mm-dd"  value="<?=$member_dob?>" class="form-control date">


                </div>

                <div class="col-sm-10">

                    <label for="postname" class="col-12 col-form-label post-label">Date of Death</label>

                    <input type="text" data-date-format="yyyy-mm-dd" name="member_date_of_death" value="<?=$member_date_of_death?>" class="form-control date">


                </div>

            </div>

            <div class="fields-member-post-container" id="sortableList">

                <?php
                if (!empty($number_rows)) {

                    for ($i = 0; $i <= $number_rows[count($number_rows) - 1]; $i++) {

                        $columns = $number_of_columns[$i];

                        $columns_classes = '';

                        switch ($columns) {

                            case 1:

                                $columns_classes = "col-12 custom-column-class member-post-single-columm";

                                break;

                            case 2:

                                $columns_classes = "col-lg-6 col-md-6 col-sm-12 custom-column-class member-post-two-columm";

                                break;

                            case 3:

                                $columns_classes = "col-lg-4 col-md-4 col-sm-12 custom-column-class member-post-three-columm";

                                break;
                        }
                        
                        
                            
                            
                        ?>

                        <div class="row member-post-row sortable-item">


                            <?php
                            if ($number_of_columns[$i] > 0):

                                for ($c = 0; $c < $number_of_columns[$i]; $c++) {

                                    // print_r($element_type);
                                    ?>

                                    <div class='<?= $columns_classes ?>'>

                                        <div class="add-element" data-row="<?= $i ?>" data-column="<?= $c ?>" data-column_number="<?= $c ?>" id="element-<?= $c ?>-<?= $i - 1 ?>">


                                            <?php
                                           $type = trim($element_type[$i][$c]);

                                            switch ($type) {

                                                case "editor_with_title_subtitle":
                                                    
                                                    ?>
                                                    <div class="element-container">

                                                       <h3 class="block-title">Editor, Description and date</h3>
                                                       
                                                        <div class="image-field">

                                                            <label></label>

                                                            <div class="view-description" style="<?=!empty($editor[$i][$c])?'display:block':''?>">
                                                                
                                                                <?php
                                                               $string = strip_tags($editor[$i][$c]);
                                                                // Check if the string length exceeds a certain limit
                                                                $limit = 100;
                                                                if (strlen($string) > $limit) {
                                                                    // Limit the string to the desired length
                                                                    $limitedString = substr($string, 0, $limit) . "...";
                                                                    echo $limitedString;
                                                                } else {
                                                                    // String is already within the limit
                                                                    echo $string;
                                                                } 
                                                                ?>
                                                                <div class="edit-text"><i class="fa fa-pen"></i></div>
                                                            </div>

                                                            <input type="hidden"  class='element_type'  data-fieldname="element_type" name="element_type[<?= $i ?>][<?= $c ?>]"
                                                                  
                                                                   value="editor_with_title_subtitle">  

                                                            <textarea data-fieldname="editor" name="editor[<?= $i ?>][<?= $c ?>]" style="display:none" 

                                                                      class="from-control editor" id="editor_with_title_subtitle--element-2-0"><?=$editor[$i][$c]?></textarea>
 <?php
                                                                 if(empty($editor[$i][$c])):
                                                                 ?>
                                                            <div data-id="editor_with_title_subtitle--element-2-0"
  
                                                                 class="click-here-to-add-descrption">Click Here to add Descrption
                                                         
                                                            </div>
                                                            

                                                            <?php
                                                            endif;
                                                            ?>


                                                        </div>

                                                        <div class="text-field">

                                                            <label>Title</label>

                                                            <input type="text" name="member_ship_title[<?= $i ?>][<?= $c ?>]"
                                                                   data-fieldname="member_ship_title" class="form-control" value="<?=$member_ship_title[$i][$c]?>">

                                                        </div>

                                                        <div class="tex-field">

                                                            <label>Sub Title</label>

                                                            <input type="text" value="<?=$member_ship_sub_title[$i][$c]?>" name="member_ship_sub_title[<?= $i ?>][<?= $c ?>]" data-fieldname="member_ship_sub_title" class="form-control">

                                                        </div>
                                                        
                                                        
                                                            <div class="tex-field"><label>Block's Content Position</label>
                                                         
                                                        <select name="title_sub_title_positioin[<?= $i ?>][<?= $c ?>]" data-fieldname="title_sub_title_positioin" class="form-control">
                                                       
                                                                <option value="">Select option</option>
                                                         <option value="top-left" 
                                                              <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-left"?'selected':''?>
                                                                 >
                                                             Top Left</option>
                                                         <option value="top-center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-center"?'selected':''?>
                                                                 >Top Center</option>
                                                         <option value="top-right"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-right"?'selected':''?>
                                                                 >Top Right</option>
                                                         <option value="center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="center"?'selected':''?>
                                                                 >Center</option>
                                                         <option value="bottom-left"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-left"?'selected':''?>
                                                                 >Bottom Left</option>
                                                         <option value="bottom-center"
                                                                  <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-centert"?'selected':''?>
                                                                
                                                                 >Bottom Center</option>
                                                         <option value="bottom-right"
                                                               <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-right"?'selected':''?>
                                                                  
                                                                 >Bottom Right</option>
                                                            </select>
                                                            </div>

                                                        <div class="click-to-add-element">Change Element</div>

                                                        <div class="remove-to-add-element">Remove</div>


                                                    </div>
                                                    <?php
                                                    break;

                                                case "full_editor":
                                                    ?>
                                                    <div class="element-container">

                                                        <h3 class="block-title">Full Width Editor</h3>
                                                        
                                                        <div class="image-field">

                                                            <label></label>

                                                            <div class="view-description" style="<?=!empty($editor[$i][$c])?'display:block':''?>">
                                                                 <?php
                                                               $string = strip_tags($editor[$i][$c]);
                                                                // Check if the string length exceeds a certain limit
                                                                $limit = 100;
                                                                if (strlen($string) > $limit) {
                                                                    // Limit the string to the desired length
                                                                    $limitedString = substr($string, 0, $limit) . "...";
                                                                    echo $limitedString;
                                                                } else {
                                                                    // String is already within the limit
                                                                    echo $string;
                                                                } 
                                                                ?> 
                                                                 <div class="edit-text"><i class="fa fa-pen"></i></div>
                                                            </div>

                                                            <div id="full-editor-container--element-2-2-0">

                                                                <input type="hidden" class='element_type'  data-fieldname="element_type" name="element_type[<?= $i ?>][<?= $c ?>]" value="full_editor"> 

                                                                <textarea style="display:none" name="editor[<?= $i ?>][<?= $c ?>]" id="full_editor--element-2-2-0" data-fieldname="editor"
                                                                          class="from-control editor"><?=$editor[$i][$c]?></textarea>

                                                                          <?php
                                                                 if(empty($editor[$i][$c])):
                                                                 ?>
                                                                <div data-id="full_editor--element-2-2-0" class="click-here-to-add-descrption">
                                                                    Click Here to add Descrption</div>
                                                                
                                                                <?php
                                                                endif;
                                                                ?>

                                                            </div>

                                                        </div>

                                                        <div class="click-to-add-element">Change Element</div>

                                                        <div class="remove-to-add-element">Remove</div>


                                                    </div>

                                                    <?php
                                                    break;

                                                case "video_with_title_subtitle":
                                                    ?>
                                                    <div class="element-container">
                                                        
                                                      <h3 class="block-title">Video, Description and date</h3>  

                                                        <div class="image-field">

                                                            <div class="member-ship-loader">

                                                            </div>

                                                            <label>Video</label>

                                                            <input type="hidden" class='element_type'  data-fieldname="element_type" name="element_type[<?= $i ?>][<?= $c ?>]" value="video_with_title_subtitle">

                                                            <input data-fieldname="member_ship_image_id" 
                                                                   value="<?=$member_ship_image_id[$i][$c]?>"
                                                                   name="member_ship_image_id[<?= $i ?>][<?= $c ?>]" type="hidden" class="member-ship-image-id">

                                                            <div class="membership-image-container">
                                                                
                                                                <div class='image-inner video-container'>
                                                                    
                                                                    <?php
                                                                    
                                                                    $attachment_metadata =wp_get_attachment_url($member_ship_image_id[$i][$c]);
                                                                    ?>
                                                                    <video controls>
                                                                    
                                                                        <source src='<?=$attachment_metadata?>' type='video/mp4'>
                                                                    
                                                                        Your browser does not support the video tag.
                                                                 
                                                                    </video> 
                                                                   
                                                                    <div class='overlay'></div>
                                                           
                                                                    <div class='edit-image'>
                                                                        
                                                                        <i class='fa fa-pen'></i>
                                                                    
                                                                    </div>
                                                                    
                                                                    <div class='delete'><i class='fa fa-trash'></i></div>
                                                                
                                                                </div>
                                                            
                                                            
                                                            </div>

                                                            <input type="file" accept="video/*" name="member_ship_video[<?= $i ?>][<?= $c ?>]" data-fieldname="member_ship_video" class="form-control">

                                                        </div>

                                                        <div class="text-field"><label>Title</label>

                                                            <input type="text" name="member_ship_title[<?= $i ?>][<?= $c ?>]"
                                                                   value="<?=$member_ship_title[$i][$c]?>"
                                                                   data-fieldname="member_ship_title" class="form-control">

                                                        </div>

                                                        <div class="tex-field"><label>Sub Title</label>

                                                            <input type="text" value="<?=$member_ship_sub_title[$i][$c]?>" name="member_ship_sub_title[<?= $i ?>][<?= $c ?>]" data-fieldname="member_ship_sub_title" class="form-control">

                                                        </div>
                                                        
                                                        <div class="tex-field"><label>Block's Content Position</label>
                                                    
                                                        <select name="title_sub_title_positioin[<?= $i ?>][<?= $c ?>]" data-fieldname="title_sub_title_positioin" class="form-control">
                                                       
                                                                <option value="">Select option</option>
                                                         <option value="top-left" 
                                                              <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-left"?'selected':''?>
                                                                 >
                                                             Top Left</option>
                                                         <option value="top-center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-center"?'selected':''?>
                                                                 >Top Center</option>
                                                         <option value="top-right"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-right"?'selected':''?>
                                                                 >Top Right</option>
                                                         <option value="center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="center"?'selected':''?>
                                                                 >Center</option>
                                                         <option value="bottom-left"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-left"?'selected':''?>
                                                                 >Bottom Left</option>
                                                         <option value="bottom-center"
                                                                  <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-centert"?'selected':''?>
                                                                
                                                                 >Bottom Center</option>
                                                         <option value="bottom-right"
                                                               <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-right"?'selected':''?>
                                                                  
                                                                 >Bottom Right</option>
                                                            </select>
                                                        </div>

                                                        <div class="click-to-add-element">Change Element</div>

                                                        <div class="remove-to-add-element">Remove</div>

                                                    </div>

                                                    <?php
                                                    break;

                                                case "image_with_title_subtitle":
                                                    ?>

                                                    <div class="element-container">
                                                        
                                                        <h3 class="block-title">Image, Description and date</h3>

                                                        <div class="image-field">

                                                            <div class="member-ship-loader"></div>

                                                            <label>Image</label>

                                                            <input data-fieldname="element_type" class='element_type'  type="hidden" name="element_type[<?= $i ?>][<?= $c ?>]" 
                                                                   value="image_with_title_subtitle"><input name="member_ship_image_id[<?= $i ?>][<?= $c ?>]" 
                                                                   data-fieldname="member_ship_image_id"  value="<?=$member_ship_image_id[$i][$c]?>" type="hidden" class="member-ship-image-id">

                                                            <div class="membership-image-container">

                                                                <div class='image-inner'>
                                                                    
                                                                    <?php
                                                                    
                                                                     $image_attributes = wp_get_attachment_image_src($member_ship_image_id[$i][$c], 'thumbnail');
                    
                                                                     if(!empty($image_attributes)){
                                                                    ?>
                                                                    
                                                                    <img src='<?=$image_attributes[0]?>' class='member-shipimage-tag' />
                                                                    
                                                                    <?php
                                                                     }
                                                                    ?>
                                                                    
                                                                    <div class='edit-image'><i class='fa fa-pen'></i></div>
                                                                    
                                                                    <div class='delete'><i class='fa fa-trash'></i></div>
                                                                
                                                                </div>
                                                            </div>

                                                            <input type="file" accept="image/*" data-fieldname="member_ship_image" name="member_ship_image[<?= $i ?>][<?= $c ?>]" 
                                                                   class="form-control">

                                                        </div>

                                                        <div class="text-field"><label>Title</label>

                                                            <input type="text"
                                                                   value="<?=$member_ship_title[$i][$c]?>"
                                                                   name="member_ship_title[<?= $i ?>][<?= $c ?>]" data-fieldname="member_ship_title" 

                                                                   class="form-control">

                                                        </div>

                                                        <div class="tex-field">

                                                            <label>Sub Title</label>

                                                            <input type="text" name="member_ship_sub_title[<?= $i ?>][<?= $c ?>]" 
                                                                   data-fieldname="member_ship_sub_title" class="form-control" value="<?=$member_ship_sub_title[$i][$c]?>">

                                                        </div>
                                                        
                                                        <div class="tex-field"><label>Block's Content Position</label>
                                                            
                                                            <?php
                                                            ?>
                                                        <select name="title_sub_title_positioin[<?= $i ?>][<?= $c ?>]" data-fieldname="title_sub_title_positioin" class="form-control">
                                                       
                                                                <option value="">Select option</option>
                                                         <option value="top-left" 
                                                              <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-left"?'selected':''?>
                                                                 >
                                                             Top Left</option>
                                                         <option value="top-center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-center"?'selected':''?>
                                                                 >Top Center</option>
                                                         <option value="top-right"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="top-right"?'selected':''?>
                                                                 >Top Right</option>
                                                         <option value="center"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="center"?'selected':''?>
                                                                 >Center</option>
                                                         <option value="bottom-left"
                                                                 <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-left"?'selected':''?>
                                                                 >Bottom Left</option>
                                                         <option value="bottom-center"
                                                                  <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-centert"?'selected':''?>
                                                                
                                                                 >Bottom Center</option>
                                                         <option value="bottom-right"
                                                               <?=!empty($title_sub_title_positioin[$i][$c]) && $title_sub_title_positioin[$i][$c]=="bottom-right"?'selected':''?>
                                                                  
                                                                 >Bottom Right</option>
                                                            </select>
                                                        </div>

                                                        <div class="click-to-add-element">Change Element</div>

                                                        <div class="remove-to-add-element">Remove</div>

                                                    </div>
                            <?php
                            break;
                            
                                               case"only_title":
                                
                                                ?>
                                             <div class="element-container">

                                                        <div class="image-field">
                                                           
                                                           <h3 class="block-title">Title</h3> 

                                                            <label></label>


                                                            <div id="full-editor-container--element-2-2-0">

                                                               <div class="text-field"><label>Title</label>
                                                                   
                                                                   <input data-fieldname="element_type" class='element_type'  type="hidden" name="element_type[<?= $i ?>][<?= $c ?>]" 
                                                                   value="only_title">

                                                            <input type="text" name="only_title[<?= $i ?>][<?= $c ?>]"
                                                                   value="<?=$only_title[$i][$c]?>"
                                                                   data-fieldname="only_title" class="form-control">

                                                        </div> 

                                                            </div>

                                                        </div>

                                                        <div class="click-to-add-element">Change Element</div>

                                                        <div class="remove-to-add-element">Remove</div>


                                                    </div>
                                            <?php
                                break;
                            
                                                default:
                                                    
                                                    ?>
                                           <div class='click-to-add-element'>Add Element</div>
                                            <?php
                    }
                    ?>


                                                 <?php
                                                            
                                                            if(!empty($column_size[$i][$c])){
                                                                
                                                                ?>
                                                            <input type="hidden" value="<?=$column_size[$i][$c]?>" name="column_size[<?= $i ?>][<?= $c ?>]">
                                                              <?php
                                                            }
                                                            ?>
                                        </div>

                                    </div>

                    <?php
                }

            endif;
            ?>
                            
                             <?php    
                      if(!empty($column_class_row)):
                    ?>
                            <input type="hidden" name="column_class_row[]" value="<?=$column_class_row[$i]?>">

                    <?php
                    
                    endif;
                    
                  if(!empty($rows_number)):  
                      ?>     
                            <input type="hidden" name="rows_number[]" value="<?=$rows_number[$i]?>">

                            <?php
                             endif;
               if(!empty( $column_number)):  
                            ?>
                            <input type="hidden" name="column_number[]" value="<?=$column_number[$i]?>">

                            <?php
                            endif;
                            ?>

                            <div class="remove">-</div>
                        </div>
                            <?php
                        }
                    }
                    ?>

            </div>
 <?php
 
 
 
                if(!empty($number_rows)):
                    
                    if(count($number_rows)<=$number_of_blocks):
                ?>
                        <div class="custom-field-container">




                            <div class="add-fields" data-rownumber="0" data-numberofblock="<?=$number_of_blocks?>">

                                <span class="plus-add-filed" >+</span>

                            </div>


                        </div>

             <?php
             endif;
             else:
                 ?>
            
             <div class="custom-field-container">




                            <div class="add-fields" data-rownumber="0" data-numberofblock="<?=$number_of_blocks?>">

                                <span class="plus-add-filed" >+</span>

                            </div>


                        </div>
            <?php
                 
                 
                endif;
                ?>


            <div class="col-12">

                <button class="btn btn-primary" type="submit"><?=(!empty($_REQUEST['id']))?'Update':'Create'?></button>

            </div>

        </form>

    <?php
else:

    echo "<p class='post-completed'> You cannot create anymore posts</p>";

endif;
?>
</div>

<div class="modal fade" id="row-column" tabindex="-1" role="dialog" aria-labelledby="update-post" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Add row</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="update-post-body">

                <form id="row-columns" method="post">


                    <div class="form-group row">

                        <label for="postname" class="col-12 col-form-label post-label">Select Number of column</label>

                        <div class="col-sm-10">

                            <select name="number_of_column" class="form-control">

                                <option value="1">1</option>

                                <option value="2">2</option>

                                <option value="3">3</option>

                            </select>
                        </div> 

                    </div>

                    <div class="form-group row">

                        <label for="postname" class="col-12 col-form-label post-label">Css Class</label>

                        <div class="col-sm-10">

                            <input name="custom_class" type="text" class="form-control" />

                        </div>



                </form>


            </div>
            <button type="submit" id="add-rows" class="btn btn-primary">Add</button>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


            </div>

        </div>

    </div>

</div>


</div>


<div class="modal fade" id="editor-data" tabindex="-1" role="dialog" aria-labelledby="editor-data" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Add Description</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="update-post-body">

                <form id="row-columns" method="post">

                    <input type="hidden" name="editor_desc_id">

                    <div class="form-group">

                        <textarea id="description-element"></textarea>

                    </div>



                </form>


            </div>
            <button type="submit" id="add-description" style="max-width:150px;" class="btn btn-primary">Add Description</button>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


            </div>

        </div>

    </div>

</div>


</div>
<div class="modal fade" id="element-column" tabindex="-1" role="dialog" aria-labelledby="update-post" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Add Element</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="update-post-body">

                <form id="row-columns" method="post">


                    <div class="form-group row">

                        <label for="postname" class="col-12 col-form-label post-label">Add Element</label>

                        <div class="col-sm-10">

                            <input type="hidden" name="element_id">

                            <input type="hidden" name="column_number">

                            <select name="elements" class="form-control">
                                
                                <option value="">Select Element</option>

                                <option value="image_with_title_subtitle">Image With Description and date</option>
                                
<!--                                <option value="only_title">Title</option>-->

                                <option value="editor_with_title_subtitle">Editor with Description and date</option>

                                <option value="video_with_title_subtitle">Video With Description and date</option>

                                <option value="full_editor">Full Width Editor</option>

                            </select>
                        </div> 

                    </div>
                    
                    <div class="form-group row" style='display:none'>
                        
                         <label for="postname" class="col-12 col-form-label post-label">Column Size</label>

                        <div class="col-sm-10">


                            <select name="column_size" class="form-control">

                                <option value="">Select Column Size</option>
                                <option value="20%">20%</option>
                                
                                 <option value="30%">30%</option>
                                 
                                <option value="40%">40%</option>
                                
                                <option value="50%">50%</option>
                                 
                                <option value="60%">60%</option>
                                 
                                <option value="80%">80%</option>
                                
                                <option value="80%">100%</option>

                            </select>
                        </div> 
                        
                        
                    </div>

                    <button type="submit" id="add-elements" class="btn btn-primary">Add</button>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


                    </div>

            </div>

        </div>

    </div>


</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    
    

function rearrange_elemets(){
     $('.row.member-post-row').each(function (index) {

                    var numberElemetn = $(this).find('.custom-column-class').length;

                    $(this).find('.add-element').each(function (childindex) {

                            $(this).find('input[name="rows_number[]"]').val(childindex)
                            
                        if ($(this).find('input').length > 0) {

                            $(this).find('input').each(function (indexelement) {

                                var fld_name = $(this).attr('data-fieldname');
                                
                                if(fld_name != "element_type"){

                                    fld_name = fld_name + '[' + index + '][' + childindex + ']';

                                    $(this).attr('name', fld_name);
                                    
                                    $(this).parents('.add-element').find('.element_type').attr('name', 'element_type'+ '[' + index + '][' + childindex + ']')
                                    

                                 }

                            })
                        }

                        if ($(this).find('textarea').length > 0) {

                            $(this).find('textarea').each(function (indexelement) {

                                var fld_name = $(this).attr('data-fieldname');

                                fld_name = fld_name + '[' + index + '][' + childindex + ']';

                                $(this).attr('name', fld_name);

                               $(this).parents('.add-element').find('.element_type').attr('name', 'element_type'+ '[' + index + '][' + childindex + ']')
                                    

                            })
                        }

                        $(this).attr('id', 'element-' + numberElemetn + '-' + index + '-' + childindex);

                        $(this).attr('data-row', index);



                    })

                    $(this).find('input[name="rows_number[]"]').val(index);
                })
}

function sortInnerElment(){
    
       $(".member-post-row").sortable({
    
                stop: function(event, ui) {

                         rearrange_elemets()

    }
  });
}
function sortElement()
{
    

$("#sortableList").sortable({
    
     stop: function(event, ui) {
         
              rearrange_elemets()
              
             setTimeout(function(){
                 
                 sortInnerElment();
                 
             }, 200)
              
           
    }
  });    
    
}  
var editorInstance;

    $(document).on('click', '.click-here-to-add-descrption', function () {

        var thisID = $(this).attr('data-id');

        $('input[name="editor_desc_id"]').val(thisID);


        editorInstance.setData('');

        $('#editor-data').modal('show');
    })
    $(document).on('click', '.remove-to-add-element', function () {

        if (confirm('Are you sure you want to remove this block?')) {

            if ($(this).parents('.member-post-row').find('.custom-column-class').length == 1) {

                $(this).parents('.row.member-post-row').remove();

            } else {

                $(this).parents('.add-element').html('<div class="click-to-add-element">Add Element</div>');
            }
            
             $('.row.member-post-row').each(function (index) {

                    var numberElemetn = $(this).find('.custom-column-class').length;

                    $(this).find('.add-element').each(function (childindex) {

                            $(this).find('input[name="rows_number[]"]').val(childindex)
                            
                        if ($(this).find('input').length > 0) {

                            $(this).find('input').each(function (indexelement) {

                                var fld_name = $(this).attr('data-fieldname');
                                
                                if(fld_name != "element_type"){

                                    fld_name = fld_name + '[' + index + '][' + childindex + ']';

                                    $(this).attr('name', fld_name);
                                    
                                    $(this).parents('.add-element').find('.element_type').attr('name', 'element_type'+ '[' + index + '][' + childindex + ']')
                                    

                                 }

                            })
                        }

                        if ($(this).find('textarea').length > 0) {

                            $(this).find('textarea').each(function (indexelement) {

                                var fld_name = $(this).attr('data-fieldname');

                                fld_name = fld_name + '[' + index + '][' + childindex + ']';

                                $(this).attr('name', fld_name);

                            })
                        }

                        $(this).attr('id', 'element-' + numberElemetn + '-' + index + '-' + childindex);

                        $(this).attr('data-row', index);



                    })

                    $(this).find('input[name="rows_number[]"]').val(index);
                })
           


        }
          sortElement();
    })

    ClassicEditor.create(document.querySelector('#post_body'))
            .then(editor => {


            })

            .catch(error => {
            });

    ClassicEditor.create(document.querySelector('#description-element'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
            });

    $(document).on('click', '.edit-text', function () {

        var content = $(this).parents('.add-element').find('textarea').val();

 var thisID = $(this).parents('.add-element').find('textarea').attr('id');
         
         $('input[name="editor_desc_id"]').val(thisID);
        editorInstance.setData(content);

        $('#editor-data').modal('show');

    })
    $(document).on('click', '#add-description', function (e) {

        e.preventDefault();



        $('#desc-success-msg').remove();

        var thisDescID = $('input[name="editor_desc_id"]').val();

        if (editorInstance) {
        } else {
        }

        $('#' + thisDescID).val(editorInstance.getData());

        if (editorInstance.getData() != '') {

            $('#' + thisDescID).next('.click-here-to-add-descrption').hide();

            var limit_description = editorInstance.getData();

            var text = $('<div>').html(limit_description).text();

            var originalText = text;

            var maxLength = 100;

            if (originalText.length > maxLength) {

                var truncatedText = originalText.substring(0, maxLength) + '...';

                truncatedText = truncatedText + '<div class="edit-text"><i class="fa fa-pen"></i></div>';

                $('#' + thisDescID).parents('.add-element').find('.view-description').html(truncatedText)

                $('#' + thisDescID).parents('.add-element').find('.view-description').fadeIn();
            } else {

                originalText = originalText + '<div class="edit-text"><i class="fa fa-pen"></i></div>';

                $('#' + thisDescID).parents('.add-element').find('.view-description').html(originalText)

                $('#' + thisDescID).parents('.add-element').find('.view-description').fadeIn();
            }

            $('#editor-data').modal('hide');




        } else {


            $(this).before('<div style="color:red" id="desc-success-msg">Please Add some description.</div>');

            setTimeout(function () {

                $('#desc-success-msg').remove();



            }, 2000)
        }
    })


    $(document).on('click', '.click-to-add-element', function () {

       $('select[name="column_size"]').val('');
       
        $('select[name="elements"]').val('');
       
       
        $('#element-column').modal('show');

        $('input[name="element_id"]').val($(this).parents('.add-element').attr('id'))

        $('input[name="column_number"]').val($(this).parents('.add-element').attr('data-column'))



    })

    $(document).on('click', '#add-elements', function (e) {

        e.preventDefault();

       
        var editorname = '';

        var thisVal = $('select[name="elements"]').val();
        
        if(thisVal ==''){
            
            $('select[name="elements"]').addClass('error');
            
            return false;
        }

        var thisColumn = $('input[name="column_number"]').val();
        
        var thisColumnSize = $('select[name="column_size"]').val();
        
        

        var html = "<div class='element-container'>";

        var thisRowID = $('input[name="element_id"]').val();

        var row_number = $('#' + thisRowID).attr('data-row');

        switch (thisVal) {

            case "only_title":
            
              html += "<h3 class='block-title'>Title</h3><div class='text-field'><label>Title</label><input type='text' \n\
            name='only_title[" + row_number + "][" + thisColumn + "]' data-fieldname='only_title'  class='form-control'> \n\
 <input data-fieldname='element_type' class='element_type' type='hidden' name='element_type[" + row_number + "][" + thisColumn + "]' \n\
value='only_title'></div>";



            break;
            case "image_with_title_subtitle":

                html += "<h3 class='block-title'>Image, Description and date</h3><div class='image-field'><div class='member-ship-loader'></div><label>Image</label>\n\
                    <input class='element_type'  data-fieldname='element_type' type='hidden' name='element_type[" + row_number + "][" + thisColumn + "]' value='image_with_title_subtitle'><input name='member_ship_image_id[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_image_id' type='hidden' \n\
                     class='member-ship-image-id'><div class='membership-image-container'>\n\
                   </div><input type='file' accept='image/*' data-fieldname='member_ship_image' name='member_ship_image[" + row_number + "][" + thisColumn + "]' class='form-control'></div>";

                html += "<div class='text-field'><label> Description</label><input type='text' \n\
            name='member_ship_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_title'  class='form-control'></div>";

                html += "<div class='tex-field'><label> Date</label><input type='text' \n\
            name='member_ship_sub_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_sub_title' class='form-control'></div>";

 html += "<div class='tex-field'><label>Block's Content Position</label>\n\
   <select name='title_sub_title_positioin[" + row_number + "][" + thisColumn + "]' \n\
data-fieldname='title_sub_title_positioin' class='form-control'>\n\
<option value=''>Select option</option>\n\
<option value='top-left'>Top Left</option>\n\
<option value='top-center'>Top Center</option>\n\
<option value='top-right'>Top Right</option>\n\
<option value='center'>Center</option>\n\
<option value='bottom-left'>Bottom Left</option>\n\
<option value='bottom-center'>Bottom Center</option>\n\
<option value='bottom-right'>Bottom Right</option></select></div>";

                break;

            case "video_with_title_subtitle":

                html += "<h3 class='block-title'>Video, Description and Date</h3><div class='image-field'><div class='member-ship-loader'></div><label>Video</label>\n\
        <input type='hidden' class='element_type'  data-fieldname='element_type' name='element_type[" + row_number + "][" + thisColumn + "]' value='video_with_title_subtitle'> <input data-fieldname='member_ship_image_id' name='member_ship_image_id[" + row_number + "][" + thisColumn + "]' type='hidden'\n\
 class='member-ship-image-id'><div class='membership-image-container'>\n\
</div><input type='file' accept='video/*' name='member_ship_video[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_video'  class='form-control'></div>";

                html += "<div class='text-field'><label>Description</label><input type='text' \n\
            name='member_ship_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_title' class='form-control'></div>";

                html += "<div class='tex-field'><label>Date</label><input type='text' \n\
            name='member_ship_sub_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_sub_title' class='form-control'></div>";

 html += "<div class='tex-field'><label>Block's Content Position</label>\n\
   <select name='title_sub_title_positioin[" + row_number + "][" + thisColumn + "]' \n\
data-fieldname='title_sub_title_positioin' class='form-control'>\n\
<option value=''>Select option</option>\n\
<option value='top-left'>Top Left</option>\n\
<option value='top-center'>Top Center</option>\n\
<option value='top-right'>Top Right</option>\n\
<option value='center'>Center</option>\n\
<option value='bottom-left'>Bottom Left</option>\n\
<option value='bottom-center'>Bottom Center</option>\n\
<option value='bottom-right'>Bottom Right</option></select></div>";
                break;

            case "editor_with_title_subtitle":


                editorname = "editor_with_title_subtitle";

                html += "<h3 class='block-title'>Text Editor, Description and date</h3><div class='image-field'><label></label><div class='view-description'></div>\n\
      <input type='hidden' class='element_type'  data-fieldname='element_type' name='element_type[" + row_number + "][" + thisColumn + "]' value='editor_with_title_subtitle'>   <textarea data-fieldname='editor' name='editor[" + row_number + "][" + thisColumn + "]' style='display:none' \n\
class='from-control editor' id='editor_with_title_subtitle--" + thisRowID + "'></textarea>\n\
<div data-id='editor_with_title_subtitle--" + thisRowID + "'\n\
 class='click-here-to-add-descrption'>Click Here to add Descrption</div></div>";

                html += "<div class='text-field'><label>Section Description</label><input type='text'\n\
     name='member_ship_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_title' class='form-control'></div>";

                html += "<div class='tex-field'><label>Date</label><input type='text' \n\
            name='member_ship_sub_title[" + row_number + "][" + thisColumn + "]' data-fieldname='member_ship_sub_title' class='form-control'></div>";

 html += "<div class='tex-field'><label>Block's Content Position</label>\n\
   <select name='title_sub_title_positioin[" + row_number + "][" + thisColumn + "]' \n\
data-fieldname='title_sub_title_positioin' class='form-control'>\n\
<option value=''>Select option</option>\n\
<option value='top-left'>Top Left</option>\n\
<option value='top-center'>Top Center</option>\n\
<option value='top-right'>Top Right</option>\n\
<option value='center'>Center</option>\n\
<option value='bottom-left'>Bottom Left</option>\n\
<option value='bottom-center'>Bottom Center</option>\n\
<option value='bottom-right'>Bottom Right</option></select></div>";

                break;

            case "full_editor":

                editorname = "full_editor";

                html += "<h3 class='block-title'>Full Width Editor</h3><div class='image-field'><label></label><div class='view-description'>\n\
         </div><div id='full-editor-container--" + thisRowID + "'>\n\
<input type='hidden' class='element_type'  data-fieldname='element_type'  name='element_type[" + row_number + "][" + thisColumn + "]' value='full_editor'> <textarea style='display:none'  name='editor[" + row_number + "][" + thisColumn + "]' \n\
 id='full_editor--" + thisRowID + "' data-fieldname='editor' class='from-control editor'></textarea>\n\
<div data-id='full_editor--" + thisRowID + "' class='click-here-to-add-descrption'>Click Here to add Descrption</div></div></div>";

                break;


        }

if(thisColumnSize !=''){
            
            
             html += "<input type='hidden' class='column-size'  data-fieldname='column_size'  name='column_size[" + row_number + "][" + thisColumn + "]' value='"+thisColumnSize+"'> ";
 

        }
        
        html += "<div class='click-to-add-element'>Change Element</div><div class='remove-to-add-element'>Remove</div>";


        $('#' + $('input[name="element_id"]').val()).html(html);

        if (editorname == "full_editor") {

            //CKEditorChange('full-editor-container--'+thisRowID);

        }

        if (editorname == "editor_with_title_subtitle") {

            // CKEditorChange('editor_with_title_subtitle--'+thisRowID);

        }


        $('#element-column').modal('hide')
        
        sortInnerElment();
      

    })
    $(document).on('click', '#add-rows', function (e) {

e.preventDefault();

        var number_of_column = $('select[name="number_of_column"]').val();

        var column_class_row = $('input[name="custom_class"]').val();

        var html = "<div class='row sortable-item member-post-row " + column_class_row + "'>";

        if ($('.member-post-row').length > 0) {

            var row_number = $('.member-post-row').length;

        } else {

            var row_number = 0

        }

        if (number_of_column == 1) {

            html += "<div class='col-12 custom-column-class member-post-single-columm'><div data-column='0' class='add-element' data-row='" + row_number + "' id='element-1-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

        }

        if (number_of_column == 2) {

            html += "<div class='col-lg-6 col-md-6 col-sm-12 custom-column-class member-post-two-columm'><div data-column='0'  class='add-element' data-row='" + row_number + "' id='element-2-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

            html += "<div class='col-lg-6 col-md-6 col-sm-12 custom-column-class member-post-two-columm'><div data-column='1'  class='add-element' data-row='" + row_number + "' id='element-2-2-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

        }

        if (number_of_column == 3) {

            html += "<div class='col-lg-4 col-md-4 col-sm-12 custom-column-class member-post-three-columm'><div data-column='0'   class='add-element' data-row='" + row_number + "' id='element-3-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

            html += "<div class='col-lg-4 col-md-4 col-sm-12 custom-column-class member-post-three-columm'><div data-column='1'  class='add-element' data-row='" + row_number + "' id='element-3-2-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

            html += "<div class='col-lg-4 col-md-4 col-sm-12 custom-column-class member-post-three-columm'><div data-column='2'  class='add-element' data-row='" + row_number + "' id='element-3-3-" + row_number + "'><div class='click-to-add-element'>Add Element</div></div></div>";

        }

        html += "<input type='hidden' name='rows_number[]' value='" + row_number + "'>";

       
         
        html += "<input type='hidden' name='column_number[]' value='" + number_of_column + "'>";

        html += "<input type='hidden' name='column_class_row[]' value='" + column_class_row + "'>";
        html += "<div class='remove'>-</div></div>";


        html += "</div>";

        $('.fields-member-post-container').append(html);

        $('#row-column').modal('hide');
        
         var thisDataBlock  = $('.add-fields').attr('data-numberofblock');
        
        var totalLength =  $('.member-post-row').length;
        
        if(totalLength>=thisDataBlock){
            
              $('.custom-field-container').hide();
            
        }
      sortElement()
      
      sortInnerElment()
      
      

    })
    
   sortElement()
  


    $('.add-fields').on('click', function () {

        
        $('input[name="custom_class"]').val('');
        
        var thisDataBlock  = $(this).attr('data-numberofblock');
        
        var totalLength =  $('.member-post-row').length;
        
        
        if(totalLength<thisDataBlock){
            
            $('#row-column').modal('show')
            
           
            
        }
        else{
            
           $('.custom-field-container').hide();
           
            alert('You cannot add any more block!');
        }
        
        
    })

    $(document).ready(function () {


        $(document).on('click', '.fields-member-post-container .remove', function () {


            if (confirm('Are you sure you want to delete this record?')) {


                $(this).parent('.row.member-post-row').remove();

             rearrange_elemets();
             
             var thisDataBlock  = $('.add-fields').attr('data-numberofblock');
        
              var totalLength =  $('.member-post-row').length;
              
              if(totalLength<thisDataBlock){
                  
                  $('.custom-field-container').fadeIn();
              }
            }
        })

        $(document).on('change', 'input[type="file"]', function (evt) {

            var thisObject = $(this);

            var fd = new FormData();

            var files = $(this)[0].files;

            if (files.length > 0) {

                fd.append('file', files[0]);

                fd.append('action', 'upload_member_post_images')

                var url_ajax = "<?= admin_url('admin-ajax.php'); ?>";

                var thisOjbec = $(this);

                $(this).parents('.add-element').find('.member-ship-loader').fadeIn();

                $.ajax({
                    url: url_ajax,

                    type: 'post',

                    dataType: 'json',

                    data: fd,

                    contentType: false,

                    processData: false,

                    success: function (response) {

                        $('.member-ship-loader').fadeOut();
                        if (response != '') {

                            console.log('we are here');

                            thisOjbec.parents('.element-container').find('input[data-fieldname="member_ship_image_id"]').val(response.attachmet_id)

                            thisOjbec.parents('.element-container').find('.membership-image-container').html(response.img)

                            thisOjbec.addClass('hide-file-field');

                            thisOjbec.parents('.element-container').find('input[type="file"]').hide();
                        }

                    }
                });
            } else {
                alert("Please select a file.");
            }

        });


        $(document).on('click', '.edit-image', function () {

            $(this).parents('.element-container').find('input[type="file"]').trigger('click');

        })

        $(document).on('click', '.membership-image-container .delete', function () {

            if (confirm('Are you sure you want to delete it?')) {

                $(this).parents('.element-container').find('input[name="member_ship_image_id[]"]').val('');

                $(this).parents('.element-container').find('input[type="file"]').fadeIn();

                $(this).parent('.image-inner').remove();


            }


        })
        
         $('.date').datepicker({ 
             dateFormat: 'yy-mm-dd',
             changeMonth: true,
             yearRange: "-100:+0",
            changeYear: true}).on('changeDate', function() {
    $(this).datepicker('hide');
  });
  
  
  $(document).on('change', 'select[name="elements"]', function(){
      
      $(this).removeClass('error');
  })
  
  $(document).on('click', 'button[data-dismiss="modal"]',function(){


$('.modal').modal('hide')


})
    })


</script>

