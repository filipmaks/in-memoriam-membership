<?php

/* 
 * Plugin Name: User memeber ship
Description: This plugin is uses to manage user membership
Author: shujauom@gmail.com
Version: 1.1.1
Author URI: http://ma.tt/
 */

define('plguin_path', plugin_dir_path( __DIR__ ).'user-membership');


define('plugins_url', plugins_url().'/user-membership');

$error='';
class userMembership {

      private  $error='';
       
    public function __construct() {
        
        global $error;
         
        
        add_filter( 'manage_users_custom_column', array($this, 'manage_custom_column_data'), 10, 3 );


        
        add_action('admin_menu', array($this, 'register_custom_menu_page'));
        
        add_action('admin_menu', array($this,'hide_other_meueu_from_contributer'), 71);
        
        
       add_action('admin_init', array($this, 'allow_contributor_uploads'));
            
        
        add_action('wp_head',  array($this, 'add_plugin_assets'));
        
        add_action('init', array($this, 'add_custom_role'));
        
        add_action( 'init', array($this, 'membership_init_function') );
        
       add_action( 'show_user_profile', array($this,'membership_profile_fields') );
        
        add_action( 'edit_user_profile', array($this,'membership_profile_fields') );
        
        add_action( 'personal_options_update', array($this,'membership_user_profile_fields' ));
        
        add_action( 'edit_user_profile_update', array($this,'membership_user_profile_fields' ));
        
       add_action( 'save_post', array($this, 'save_custom_field') );

        add_action( 'wp_ajax_load_post_form', array($this, 'load_post_form'));

       add_action( 'wp_ajax_nopriv_load_post_form',  array($this,'load_post_form'));
       
        add_action( 'wp_ajax_save_membership_users', array($this, 'save_membership_users'));

       add_action( 'wp_ajax_nopriv_save_membership_users',  array($this,'save_membership_users'));
       

        add_action('init',  array($this,'membership_post_type'));
        
       add_action( 'add_meta_boxes_membership',  array($this, 'membership_metabox') );
        
      // add_action('pre_get_posts', array($this, 'query_set_only_author' ));
        
        add_action('admin_footer', array($this, 'custom_admin_js'));
        
        add_filter('wp_handle_upload_prefilter', array($this, 'custom_upload_filter') );
        
        add_filter( 'ajax_query_attachments_args', array($this, 'show_current_user_attachments'), 10, 1 );
        
        add_filter( 'the_content', array($this, 'membership_single_page_slider' ));

        
        add_action('after_setup_theme',  array($this,'hide_membership_admin_bar'));
        
        add_filter( 'manage_users_columns', array($this, 'customize_postscount_columns') );

        add_action( 'pre_get_posts', array($this, 'filter_custom_post_type_by_author') );
      
    //    add_filter( 'wp_enqueue_scripts', array($this, 'change_membership_default_jquery'), PHP_INT_MAX );

        add_action( 'wp_ajax_upload_member_post_images', array($this, 'upload_member_post_images'));

       add_action( 'wp_ajax_nopriv_upload_member_post_images',  array($this,'upload_member_post_images'));
       
        
    }
    /*
     * 
     * 
     * Upload user member post images
     */
    public function upload_member_post_images(){
        
        
        
        $upload_dir = wp_upload_dir(); // Get the upload directory
        
      
        $image_name = 'post-'.get_current_user_id().'_'.time().'_'.$_FILES['file']['name']; // Set the name of your image file
        
        $target_file = $upload_dir['path'] . '/' . $image_name; // Set the target path for your image file

        $file_path = $_FILES['file']['tmp_name'];
// Check if the file already exists in the upload directory
            if (file_exists($target_file)) {
                
                echo "Sorry, file already exists.";
                
            } else {
                
                
                $image_data = file_get_contents($_FILES['file']['tmp_name']); // Get the image data from the uploaded file
               
                $uploaded_file = wp_upload_bits($image_name, null, $image_data, date('Y/m')); // Upload the file to the specified directory

                // If wp_upload_bits() returned an error, display it to the user
                
                
                if (!empty($uploaded_file['error'])) {
                    
                   
                } else {
                    
                    $attachment = array(
                        
                        'guid'           => $uploaded_file['url'],
                        
                        'post_mime_type' => $uploaded_file['type'],
                        
                        'post_title'     => sanitize_file_name($image_name),
                        
                        'post_content'   => '',
                        
                        'post_status'    => 'inherit'
                    );
                    
                   $attachment_id = wp_insert_attachment($attachment, $uploaded_file['file']);

             
                    // Update the attachment metadata
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
                    
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                    
                    $mime_type = mime_content_type($file_path);
                    
                    $image_array = array();
                    
                    $image_array['attachmet_id'] = $attachment_id;
                    
                    if (strpos($mime_type, 'image/') === 0) {
                            
                     $image_attributes = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    
                    if ($image_attributes) {

                        $image_url = $image_attributes[0];
                        
                        $image_array['img'] = "<div class='image-inner'><img src='$image_url' class='member-shipimage-tag' /><div class='edit-image'><i class='fa fa-pen'></i></div><div class='delete'><i class='fa fa-trash'></i></div></div>";
                   
                        }
                        
                    } elseif (strpos($mime_type, 'video/') === 0) {
                        
                       $attachment_metadata =wp_get_attachment_url($attachment_id);
                       
                       $image_array['img']= "<div class='image-inner video-container'><video controls>
                                                <source src='$attachment_metadata' type='video/mp4'>
                                                Your browser does not support the video tag.
                                              </video> 
                                               <div class='overlay'></div>
                                              <div class='edit-image'><i class='fa fa-pen'></i></div><div class='delete'><i class='fa fa-trash'></i></div></div>";
                 
                        
                    } 
                    
                    
                    
                    echo json_encode($image_array);

                    
                }
            }
            
            exit;
    }
    /*
     * 
     * 
     * Remove default jquery
     */
   public function change_membership_default_jquery( ){
          
        wp_dequeue_script( 'jquery');
        
        wp_deregister_script( 'jquery');   
       }
    /*
     * 
     * Manage custom column data
     * 
     */
    
    function manage_custom_column_data( $val, $column_name, $user_id ) {

     if ( ! current_user_can( 'edit_user' ) ) {
        return false;
     }

    $val = '';
    if ( $column_name == 'post_count') {
        
        
        $current_user = get_userdata($user_id);
        
        $user_roles = $current_user->roles;
        
        if(in_array('user-membership', $user_roles)):
            
            
                $user_level = get_user_meta($current_user->ID, 'member_level', true);

                $membership_name = get_option('membership_name');

                $membership_number_posts = get_option('membership_number_posts');

                $membership_number_posts_images = get_option('membership_number_posts_images');

                $membership_costs = get_option('membership_costs');

                $memebership_status = get_option('memebership_status');

                $number_of_post = 0;

                $number_of_images = 0;

                if (!empty($membership_name)):

                    foreach ($membership_name as $key => $single):


                        if ($single == $user_level):

                           $number_of_post = !empty($membership_number_posts[$key]) ? $membership_number_posts[$key] : 0;

                            $number_of_images = !empty($membership_number_posts_images[$key]) ? $membership_number_posts_images[$key] : 0;

                        endif;

                    endforeach;

                endif;

            
              $user_id = $user_id; // Replace with the desired user ID
                
             $args = array(
                    'author' => $user_id,
                    'post_type' => 'membership-posts',
                    'post_status' => 'publish',
                    'posts_per_page' => -1, // Retrieve all posts
                );

                $query = new WP_Query( $args );

                $count = $query->found_posts;
                
                if($number_of_post>=$count){
                    
                    $remaining_posts =  $number_of_post - $count;
                    
                }else{

                        $remaining_posts = 0;
                }
                
                $post_url = site_url().'/wp-admin/edit.php?post_type=membership-posts&auther='.$user_id;
                
                $html = "<a href='$post_url'>";
                
                    $html.="<div class='item-1'><strong>Total Posts Created:</strong><span>$count</span></div>";
                    
                    $html.="<div class='item-1'><strong>Remaining Posts:</strong><span>$remaining_posts</span></div>";
                
              return  $html.="</a>";
            
        endif;
        
        $args = array(
                    'author' => $user_id,
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1, // Retrieve all posts
                );

                $query = new WP_Query( $args );

                
                $count = $query->found_posts;
                
                $post_url = site_url().'/wp-admin/edit.php?auther='.$user_id;
                
                $html = "<a href='$post_url'>$count";
                
              return  $html.="</a>";
    }
   return $val;
}
    /*
     * 
     * 
     * Filter post type membership by auther id
     */
    function filter_custom_post_type_by_author( $query ) {
   
    
                if(is_admin()){


                    // Check if the query is for the desired custom post type
                    if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'membership-posts' ) {

                        // Check if the user is logged in and has a valid author ID
                      $author_id = $_GET['auther'];

                        if ( $author_id ) {

                            // Set the author ID as a query parameter
                            $query->set( 'author', $author_id );
                        }
                    }
                    }
                }
    
    /*
     * 
     * Customize post count column
     */
    
       public function customize_postscount_columns( $columns ) {

            $newCOlumns = array();

            foreach($columns as $key=>$singleC){

                if($key =='posts'){

                    $newCOlumns['post_count'] = $singleC;

                }else{

                    $newCOlumns[$key] = $singleC;
                }
            }
              return $newCOlumns;
            }


    /**
     * 
     * 
     */
    
function hide_membership_admin_bar() {
    
   if (current_user_can('user-membership') ) {
       
     show_admin_bar(false); // this line isn't essentially needed by default...
     
   } 
}


    
    /**
     * 
     * 
     * Single members ship post slider 
     */
    
function membership_single_page_slider( $content ) {
    

    if ( is_single() && 'membership-posts' == get_post_type() ) {
        
       
        
            ob_start();                      // start capturing output
        
            include(plguin_path . '/frontend/single-slider.php');   // execute the file
            
            $content_slide = ob_get_contents();    // get the contents from the buffer
            
            ob_end_clean();   

        return $content_slide;
        
    } else {
        
        return $content;
    }
}

    /**
     * 
     * 
     * Save suer
     */
    
    public function save_membership_users(){
        
        if(!empty($_POST)){
    
    
    $member_email = sanitize_text_field( $_POST['member_email'] );

    $member_first_name= sanitize_text_field( $_POST['member_first_name'] );
    
    $member_last_name = sanitize_text_field( $_POST['member_last_name'] );
    
    $member_password = trim($_POST['member_password']);
    
    $member_level = sanitize_text_field( $_POST['member_level'] );
    
    if(email_exists($member_email)){
        
        echo "error_email";
        exit;
    }
        
        else{
            
            
            $user_id = wp_insert_user( array(
                
                'user_login' => $member_email,
                
                'user_pass' => $member_password,
                
                'user_email' => $member_email,
                
                'first_name' => $member_first_name,
                
                'last_name' => $member_last_name,
                
                'display_name' => "$member_first_name $member_last_name",
                
                'role' => 'user-membership'
              ));
        
              $_SESSION['user_member_id'] = $user_id;
            
               $cookie_name = "user_member_id";
               
                $cookie_value = $user_id;
               
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

              update_user_meta($user_id, 'member_approvel', 'no');
              
             update_user_meta($user_id, 'member_payment_status', 'pending');
              
              update_user_meta($user_id, 'member_payment', $_POST['member_payment']);
             
             update_user_meta($user_id, 'member_level', $member_level);
             
              update_user_meta($user_id, 'member_date_of_death', $_POST['member_date_of_death']);
    
              update_user_meta($user_id, 'member_date_of_birth', $_POST['member_date_of_birth']);

            update_user_meta($user_id, 'member_phone', $_POST['member_phone']);

            update_user_meta($user_id, 'member_invoice', $_POST['member_invoice']);
             
             $user = get_user_by( 'ID', $user_id );
            
             $user->add_role( 'user-membership' );
  
              $user_registration = true;
             
             $payment_type =  $_POST['member_payment'];
              
              $html ="<h2>Hello Dear</h2>";
              
              $html .="<p>The user with following info registered!</p>";
              
              $html = "<table>";
              
              $html .= "<tr><td>First Name</td><td>$member_first_name</td></tr>";
              
              $html .= "<tr><td>Last Name</td><td>$member_last_name</td></tr>";
              
              $html .= "<tr><td>Email</td><td>$member_email</td></tr>";
              
              $html .= "<tr><td>Member ship Level</td><td>$member_level</td></tr></table>";
              
              $html .= "<tr><td>Payment Type</td><td>$payment_type</td></tr></table>";
              
              if($_POST['member_payment'] == "direct"):
                  
                  $html .= "<tr><td>Payment Status</td><td>Pending</td></tr></table>";
                  
              endif;
              
              $from  ="In Memoriam";
              
              $admin_url = admin_url().'user-edit.php?user_id='.$user_id;
              
              $html .= "<p>Please <a href='$admin_url'><strong>click here</strong></a> to approver their account! OR copy the following link!</p>";
              
              $html .= "<p>$admin_url</p>";
              
              $html .="<p><strong>Thanks,</strong></p>";
                  
                $to = get_option('admin_email');
                
                $subject = 'New Member Registration';
                
               $body = $html;
                
                                         $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);

                wp_mail( $to, $subject, $body, $headers );
                
                
                
                
              $html_user ="<h2>Congratulation!</h2>";
              
              $html_user .="<p>You are registered successfully, waiting admin to approve your account. </p>";
              
               $html_user .="<p><strong>Thanks,</strong></p>";
              
               $to_user = $member_email;
                
                $subject = 'Membership Account';
            
                $body = $html_user;
                
                                      $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);
                wp_mail( $to_user, $subject, $body, $headers );
                
                if($_POST['member_payment'] == "manual"):
                    
                echo 'ok';
                
                endif;
        
    }
    
    if($_POST['member_payment'] == "direct"):
        
        echo "direct";
        
    endif;
    
        }
        
        exit;
    }
    /**
     * 
     * save csutom fields
     */
    public function save_custom_field($post_id){
        
        global $post;
        
        if($post->post_type =="membership"):
            
            update_post_meta($post_id, 'gallery_images', $_POST['gallery_images']);
            
        endif;
    }
    /**
     * 
     * 
     * Conttibutor role to upload images
     */
    public function allow_contributor_uploads(){
        
        $membership= get_role('user-membership');
//        
       $membership->add_cap('upload_files');
//        
         $membership->add_cap('edit_post');
    }
    /**
     * 
     * 
     * membership custom fields
     */
    public function membership_metabox(){
        
        	add_meta_box( 'meta-box-id', __( 'Custom fields', 'textdomain' ), array($this, 'render_membership'), 'membership' );

    }
    
    /**
     * 
     * 
     * 
     * Hide menue
     */
    public function hide_other_meueu_from_contributer(){
        
        
	 $user_meta = get_userdata(get_current_user_id());
	   
    $user_roles = $user_meta->roles;
    
    
    
    
    if(in_array('user-membership', $user_roles)):
     
        
         foreach($GLOBALS[ 'menu' ] as $position => $items) {
                foreach($items as $key => $item) {					
                    
                    if($items[2] !="edit.php?post_type=membership"):
                        
                         remove_menu_page( $items[2] );
                        
                    endif;
                       
                  
                }
            }
    
        
	endif;

        
    }
    /**

      render custom felds
***/
    public function render_membership(){

        include_once plguin_path.'/admin/custom-fields.php';
    }
    /**
     * 
     * 
     */
   public function membership_post_type() {
    /*
     * The $labels describes how the post type appears.
     */
    $labels = array(
        'name'          => 'Memberships', // Plural name
        'singular_name' => 'Membership'   // Singular name
    );

    /*
     * The $supports parameter describes what the post type supports
     */
    $supports = array(
        'title',        // Post title
        'editor',  
        'thumbnail'// Supports by custom fields
    );

    /*
     * The $args parameter holds important parameters for the custom post type
     */
    $args = array(
        'labels'              => $labels,
        'description'         => 'Post type post membership', // Description
        'supports'            => $supports,
        'taxonomies'          => array( 'category', 'post_tag' ), // Allowed taxonomies
        'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
        'public'              => true,  // Makes the post type public
        'show_ui'             => true,  // Displays an interface for this post type
        'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
        'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
        'show_in_admin_bar'   => true,  // Displays in the black admin bar
        'menu_position'       => 5,     // The position number in the left menu
        'menu_icon'           => true,  // The URL for the icon used for this post type
        'can_export'          => true,  // Allows content export using Tools -> Export
        'has_archive'         => true,  // Enables post type archive (by month, date, or year)
        'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
        'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
        'capability_type'     => 'post' // Allows read,f edit, delete like “Post”
    );

    register_post_type('membership-posts', $args); //Create a post type with the slug is ‘product’ and arguments in $args.
}
    /**
     * 
     * 
     * Load post form
     */
    public function load_post_form(){
        
        include_once plguin_path.'/frontend/ajax/post-form.php';
        
        exit;
    }
    /*
     * 
     * update user profile
     */
    
    
    public function membership_user_profile_fields($user_id){
        
        
         $user_meta = get_userdata($user_id);
         
        
        if(!empty($_POST['member_approvel'])):
            
             update_user_meta($user_id, 'member_payment_status', $_POST['member_payment_status']);
            
        endif;
        if(!empty($_POST['member_approvel'])):
            
                
            update_user_meta($user_id, 'member_approvel', $_POST['member_approvel']);
        
             $html ="<h2>Hello Dear</h2>, <br></br>";
              
              $html .="<p>Your account is approved by admin, you can login with your credentials now!</p></br>";
              
              
                $to = $user_meta->data->user_email;
                
                $subject = 'Account Approved';
                
                $body = $html;
                
                             $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);

                wp_mail( $to, $subject, $body, $headers );
            
                
        endif;
        
    }


    /*
     * 
     * 
     * user profile fields
     */
    
    public function membership_profile_fields($user){
        
         $user_meta = get_userdata($user->ID);
        
         $user_roles = $user_meta->roles;
         
        
        if ( !empty($user_roles) && in_array( 'user-membership', $user_roles, true ) ) {
            
       
        ?>

         <h3><?php _e("Extra profile information", "blank"); ?></h3>

    <table class="form-table">
    
        <tr>
            
        <th><label for="address"><?php _e("Approve it"); ?></label></th>
        
        <td>
        
            <input type="hidden" name="member_approvel" id="member_approvel" 
            
                   value="<?php echo esc_attr( get_the_author_meta( 'member_approvel', $user->ID ) ); ?>"
                   
                   class="regular-text" />
            
            <input type="checkbox" class="status_checkbox" <?=(get_the_author_meta( 'member_approvel', $user->ID )=="yes")?'checked':''?>>
            
            
        </td>
    </tr>
    
    
    <?php
   $payment_status =  esc_attr( get_the_author_meta( 'member_payment_status', $user->ID ) );
   
    ?>
        <tr>
            
        <th><label for="address"><?php _e("Payment Status"); ?></label></th>
        
        <td>
         
            <select name="member_payment_status">
                
                <option value="">Select Payment Status</option>
                
                <option <?php echo  ($payment_status == "pending")?"selected":'' ?> value="pending">Pending</option>
                
                <option <?php echo  ($payment_status == "completed")?"selected":'' ?> value="completed">Completed</option>
                
            </select>
            
        </td>
    </tr>
    
    </table>
         
         <script type="text/javascript">
         
                
      jQuery(document).on('click', '.status_checkbox',function(){
          
           if (jQuery(this).prop('checked')==true){ 
        
               jQuery(this).prev('#member_approvel').val('yes');
               
            }
            else{
                
                jQuery(this).prev('#member_approvel').val('no');
                
            }
          
      })
         
         </script>

     <?php
     
      }
      
    }


    /*
     * 
     * Set error
     */
    
    public function set_error($error){
        
        $this->error = $error;
}
    
    /**
     * 
     * Get error
     */

    public function get_error(){
      
        return $this->error;
    }

    /*
     * 
     * 
     * do login
     */
    public function membership_init_function(){
       
         $this->create_required_pages();
         
       // print_r($user_meta);
         if(!empty($_POST['member_login_email'])):
    
    
    $member_email = sanitize_text_field( $_POST['member_login_email'] );

    $member_password = trim($_POST['member_password'] );
    
    $username = $member_email;
    
    $user = get_user_by('login', $username );
    
    if(!empty($user)){
    
    $member_approvel = get_user_meta($user->ID, 'member_approvel', true);

     $user = wp_authenticate($member_email, $member_password);
     
   
     if(!empty($user)):
     
         if(!empty($user->roles)):
             
         $user_roles = $user->roles;
     
     if ( !empty($user_roles) && in_array( 'administrator', $user_roles, true ) ) {
         
          $admin_url  = admin_url();
       
        echo "<script> window.location.href='$admin_url'</script>";
          
          exit;
      }
      
      endif;

endif;

    }
     

// Redirect URL //
    
 
if ( !is_wp_error( $user ) )
{
     if($member_approvel == 'no'):
        
          $_SESSION['login_error'] =  "Your account is not approved by admin yet!";
   
          
     wp_logout();
     
         else:
             
             
               wp_set_current_user (  $user->data->ID ); 
                
                  
                  wp_set_auth_cookie  (  $user->data->ID );
         
               
                  
                
                  
            $redirect_to = site_url('/user-profile');
            
            
           
            
           echo "<script> window.location.href='$redirect_to'</script>";
            
            exit();
        
    endif;
     
   
}
else{
    
     
     $_SESSION['login_error'] =  "Login credentials are invalid";
     
     
      
}
    
   
    
endif;
    }


    /*
     * 
     * add custom role
     */
    public function add_custom_role(){
        
          add_role( 'user-membership', 'user-membership', array(
		'read'         => false,  // true allows this capability
		'edit_posts'   => false,
		'delete_posts' => false,
                'upload_files' => false,
              // Use false to explicitly deny
    ) );
    }
    /**
     * 
     * Load plugin css, js
     */
    public  function add_plugin_assets(){
        
         wp_enqueue_script( 'script', plugins_url . '/assets/member-ship.js', array( 'jquery' ), 1.2, true);
         
         wp_enqueue_style('user-membership', plugins_url . '/assets/member-ship.css', array(), '0.2.0', 'all');
         wp_enqueue_style('user-membership-new', plugins_url . '/assets/membership-new.css', array(), '0.2.0', 'all');
        
    }


    /*
     * 
     * 
     * add settings menu
     */
    function register_custom_menu_page() {
        
    add_menu_page(
		__( 'Membership settings', 'textdomain' ),
		'Membership settings',
		'manage_options',
		'user-membership/admin/settings/user-membership.php',
		'',
		plugins_url( 'myplugin/images/icon.png' ),
		6
	);
    }
   
    /*
     * 
     * Create requried pages
     */
    public function create_required_pages(){
        
         $page_slug = "user-membership";
         
         if($this->check_pageby_slug($page_slug)){
             
              $this->create_membership_pages($page_slug, 'User Membership', '[user_registration]');
         }
         
         $page_slug = "membership-login";
         
         if($this->check_pageby_slug($page_slug)){
             
              $this->create_membership_pages($page_slug, 'Membership Login', '[user_login]');
         }
         
         
         $page_slug = "user-profile";
         
         if($this->check_pageby_slug($page_slug)){
             
              $this->create_membership_pages($page_slug, 'Member Profiles', '[user_profile]');
         }
         
          $page_slug = "update-member-profile";
         
         if($this->check_pageby_slug($page_slug)){
             
              $this->create_membership_pages($page_slug, 'Update Membership profile', '[member_update_profile]');
         }
         
         
          $page_slug = "member-create-post";
         
         if($this->check_pageby_slug($page_slug)){
             
              $this->create_membership_pages($page_slug, 'Member create Post', '[member_create_post]');
         }
         
         
        
    }
    /*
     * 
     * Create page
     */
    
    public function create_membership_pages($slug, $page_title, $content){

        $page_id = wp_insert_post(
                      array(
                        'comment_status' => 'close',
                        'ping_status'    => 'close',
                        'post_author'    => 1,
                        'post_title'     => $page_title,
                        'post_name'      => $slug,
                        'post_status'    => 'publish',
                        'post_content'   => $content,
                        'post_type'      => 'page',
                        )
                    );
    }


    /*
    * 
    * check page by slug
    */
   public function check_pageby_slug($page_slug){

       $page = get_page_by_path( $page_slug , OBJECT );
       
       if(empty($page)){
           
           return true;
       }
       
       if($page->post_name !=$page_slug){
           
           return true;
       }
       
       return false;
       
       }
       
       /**
        * 
        * 
        * Only current user posts
        * 
        */
       public function query_set_only_author( $wp_query ) {
           
    global $current_user;
    
    
    $user_meta = get_userdata($current_user->ID);
    
     $user_roles = $user_meta->roles;
    
    
     
    if(!empty($user_roles) && in_array('user-membership', $user_roles)):
        
         $wp_query->set( 'author', $current_user->ID );
        
    endif;
     
    
}
/**
 * 
 * Add js to admin section
 * 
 */
function custom_admin_js() {
    global $post, $current_user;
    
    $user_meta = get_userdata(get_current_user_id());
	   
    $user_roles = $user_meta->roles;
    
    
 
    
    
    if(in_array('user-membership', $user_roles)):
    
         
       echo "<style>
                #adminmenu a[href='admin.php?page=jetpack#/dashboard'] {
            display: none;
                   }
.remove-icon {
  position: absolute;
  top: 0;
  right: 0;
  color: ;
  cursor: pointer;
}
li#thumbler-menu {
    display: none;
}
li#wp-admin-bar-new-content {
    display: none;
}
li#wp-admin-bar-comments {
    display: none;
}
#adminmenu a[href='edit.php?post_type=product&page=product-reviews'] {
    display: none;}
    div#dashboard-widgets-wrap {
    display: none;
}
#screen-meta-links+.jitm-card {
    display: none;
}
div#screen-meta-links {
    display: none;
}
</style>";
    

    
    if($post->post_type =="membership"):
        
        
        $user_level = get_user_meta($current_user->ID, 'member_level', true);

      $membership_name = get_option('membership_name');

      $membership_number_posts = get_option('membership_number_posts');

      $membership_number_posts_images = get_option('membership_number_posts_images');

      $membership_costs = get_option('membership_costs');

      $memebership_status = get_option('memebership_status');

      $number_of_post = 0;

      $number_of_images = 0;

      if (!empty($membership_name)):

            foreach ($membership_name as $key => $single):


                if ($single == $user_level):

                   $number_of_post = !empty($membership_number_posts[$key]) ? $membership_number_posts[$key] : 0;

                    $number_of_images = !empty($membership_number_posts_images[$key]) ? $membership_number_posts_images[$key] : 0;

                endif;

            endforeach;

endif;
        

$args = array(
    'orderby' => 'post_date',
    'order' => 'DESC',
    'author' => $current_user->ID,
    'post_status' => array('publish', 'draft', 'pending'),
    'post_type' => 'membership',
);


$all_post = new WP_Query($args);

if ($all_post->post_count >= $number_of_post):


    $html = "<p style='color:red; display:inline;'>You cannot create more posts</p>";
   ?><script type="text/javascript">

          jQuery(document).ready(function(){
              
              <?php
              
                 if($_GET['action'] =="edit"):
                     
                     echo " jQuery('.page-title-action').hide()";
                 
                     else:
                     
                    ?> 
                            jQuery('.page-title-action').html("<p style='color:red; display:inline;'>You cannot create more posts</p>");
                    <?php     
                 endif;
              ?>
              
             
              
              jQuery('.page-title-action').attr('href', "#")
              
              
          })
   
   </script>
   
   <?php

endif;
     
    
        
    endif;
    
    endif;
    
}

public function checkuser_limit_images_posts(){
    
    $user_id = get_current_user_id();
    
     $user_level = get_user_meta($user_id, 'member_level', true);

      $membership_name = get_option('membership_name');

      $membership_number_posts = get_option('membership_number_posts');

      $membership_number_posts_images = get_option('membership_number_posts_images');

      $membership_costs = get_option('membership_costs');

      $memebership_status = get_option('memebership_status');

      $number_of_post = 0;

      $number_of_images = 0;
      
      $limit_array = array();

      if (!empty($membership_name)):

            foreach ($membership_name as $key => $single):


                if ($single == $user_level):

                   $number_of_post = !empty($membership_number_posts[$key]) ? $membership_number_posts[$key] : 0;

                   $limit_array['number_of_posts'] = $number_of_post;
                   
                    $number_of_images = !empty($membership_number_posts_images[$key]) ? $membership_number_posts_images[$key] : 0;

                    $limit_array['number_of_images'] = $number_of_images;
                    
                endif;

            endforeach;

endif;

return $limit_array;
       
    
}

public  function custom_upload_filter( $file ){
    
       $user_id = get_current_user_id();
    
    
    if( $user_id ) {
        
        $user_meta = get_userdata(get_current_user_id());
	   
    $user_roles = $user_meta->roles;
    
    if(in_array('user-membership', $user_roles)):
        
        $the_query = new WP_Query( array( 'post_type' => 'attachment',
            'post_status' => 'inherit', 'author' => $user_id) );
       
        
        $limit_array  = $this->checkuser_limit_images_posts();
        
        
        if($the_query->post_count>=$limit_array['number_of_images'])
    
           return $file['error'] = __( 'You have used your space quota. Please delete files before uploading.' );
 
    
           
        
      endif;
      
        return $file;
    }
    
   
}

public function show_current_user_attachments( $query = array() ) {
    
    $user_id = get_current_user_id();
    
    
    if( $user_id ) {
        
        $user_meta = get_userdata(get_current_user_id());
	   
    $user_roles = $user_meta->roles;
    
    if(in_array('user-membership', $user_roles)):
        
        $query['author'] = $user_id;
        
      endif;
    }
    
    return $query;
}


/**
 * 
 * 
 * User registration short code
 */

 public static function user_registration_function($atts){
     
     $atts = shortcode_atts(
    array(
    'path' => 'NULL',
    ), $atts, 'include' );

    ob_start();
    
   include_once  plguin_path.'/frontend/user-registration.php';
    
    return ob_get_clean();
 }
 
 public static function member_update_profile_function($atts){
     
     $atts = shortcode_atts(
    array(
    'path' => 'NULL',
    ), $atts, 'include' );

    ob_start();
    
   include_once  plguin_path.'/frontend/user-profile-update.php';
    
    return ob_get_clean();
 }
 
 public static function user_profile_function($atts){
     
     $atts = shortcode_atts(
    array(
    'path' => 'NULL',
    ), $atts, 'include' );

    ob_start();
    
   include_once  plguin_path.'/frontend/user-profile.php';
    
    return ob_get_clean();
 }
 
 public static function user_login_function($atts){
     
     $atts = shortcode_atts(
    array(
    'path' => 'NULL',
    ), $atts, 'include' );

    ob_start();
    
   include_once  plguin_path.'/frontend/user-login.php';
    
    return ob_get_clean();
 }
 
 public static function member_create_post_function($atts){
     
      $atts = shortcode_atts(
    array(
    'path' => 'NULL',
    ), $atts, 'include' );

    ob_start();
    
   include_once  plguin_path.'/frontend/member-create-post.php';
    
    return ob_get_clean();
     
 }
 
}

new userMembership();



add_shortcode( 'user_registration', array( 'userMembership', 'user_registration_function' ) );

add_shortcode( 'user_login', array( 'userMembership', 'user_login_function' ) );

add_shortcode( 'user_profile', array( 'userMembership', 'user_profile_function' ) );

add_shortcode( 'member_update_profile', array( 'userMembership', 'member_update_profile_function' ) );

add_shortcode( 'member_create_post', array( 'userMembership', 'member_create_post_function' ) );

 function member_ship_build_meta_box( $post ){
     
     include_once plguin_path.'/admin/custom-fields.php';
     
 }
function membership_add_meta_box( $post ){
    
    add_meta_box( 'membership_meta_box_id', 'Details', 'member_ship_build_meta_box', 'membership-posts');
    
}
add_action( 'add_meta_boxes', 'membership_add_meta_box' );

function membership_save_meta_box_data( $post_id ){
    
   if(!empty($_POST)):
    
  
    
    foreach($_POST as $key=>$single):
        
        if(!empty($single) && $key!="post_title" && $key!='content'):
            
            update_post_meta($post_id, $key, $single);
        
        endif;
        
    
        
    endforeach;
    
    endif; 

}
add_action( 'save_post_membership-posts', 'membership_save_meta_box_data', 10, 2 );

if(session_id() === "") {
    
    session_start();
    
}
