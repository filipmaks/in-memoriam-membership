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
if(!is_user_logged_in()){
    
    ?>

<script type="text/javascript">

  window.location.href= "<?= site_url()?>";

</script>
<?php
}


$current_user = wp_get_current_user();

if(!empty($_GET['action']) && $_GET['action'] =="delete" && !empty($_GET['post_id'])):
    

   // wp_delete_post($_GET['post_id']); 
    
     $user_name = $current_user->data->display_name;

    $post = get_post($_GET['post_id']);

       $post_id = wp_update_post(
            array(
                'ID' => $_GET['post_id'],
                'post_status' => 'trash',
            )
    );
    

$post_title =  $post->post_title;
            
            $admin_url = admin_url()."post.php?post=$post_id&action=edit";
              
             $html ="<h2>Hello Dear</h2>, <br></br>";
             
              $html .= "<br /><p> Korisnik $user_name trazi dozvolu da obrise post: $post_title.  <a href='$admin_url'><strong>Klikni ovde</strong></a> da odobris. Ili idi na sledeci link da</p>";
                            
              $html .= "<br /><p>$admin_url</p>";
              
              $html .="<br /><br/><p><strong>Hvala,</strong></p></br>";
                  
              $to =  get_option('admin_email');
                
                $subject = 'In Memoriam - nova registracija';
                
                $body = $html;
                
                                 $headers = array(
    'From: In Memoriam <'.get_bloginfo('admin_email').'>',
    'Content-Type: text/html; charset=UTF-8',
);

                wp_mail( $to, $subject, $body, $headers );
endif;

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

$member_email = "";

$email_exist_error = false;

$user_registration = false;


$exceed_image_message = '';

if (!empty($_POST['post_name'])):
    
    
    $images_exceeds = true;

if (!empty($_FILES["post_images"]["name"]) && count($_FILES["post_images"]["name"])>$number_of_images) {
        
        
    $images_exceeds = false;
    
    $exceed_image_message = "Ne mozes da kreiras post sa vise postova. Ispunio si limit.";
        
    }
    
  if($images_exceeds):

      
    if(!empty($_POST['membership_post_id'])):
        
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
        
    endif;
    

    
   

    if (isset($_FILES["post_images"]["name"]) && !empty($_FILES["post_images"]["name"][0])) {

         

        $image_ids = array();

        foreach ($_FILES["post_images"]['name'] as $key => $single) {



            $upload = wp_upload_bits($single, null, file_get_contents($_FILES["post_images"]['tmp_name'][$key]));

            if (!$upload['error']) {

                $post_id = $post_id;

//set post id to which you need to add featured image
                $filename = $upload['file'];

                $wp_filetype = wp_check_filetype($filename, null);

                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attachment_id = wp_insert_attachment($attachment, $filename, $post_id);


                if (!is_wp_error($attachment_id)) {

                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);

                    wp_update_attachment_metadata($attachment_id, $attachment_data);

                    if ($key == 0) {

                        set_post_thumbnail($post_id, $attachment_id);
                    } 
                    
                     $image_ids[] = $attachment_id;
                }
            }
        }

        update_post_meta($post_id, 'gallery_membership', $image_ids);

        update_post_meta($post_id, 'post_auther', $_POST['user_id']);
    }
    endif;
endif;



?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />

<!-- Create a tag that we will use as the editable area. -->
<!-- You can use a div tag as well. -->


<!-- Include Editor JS files. -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>



<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="container user-membership-profile">


    <?php
    
    
    if(!empty($_SESSION['message_create_post'])):
        
        ?>
    
    <div class="alert alert-success alert-dismissible fade show" role="alert">
     
        <?=$_SESSION['message_create_post'] ?>
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span> 

        </button>

      </div>
    
     <?php
        
     
     $_SESSION['message_create_post'] ='';
    endif;
    if(!empty($exceed_image_message)):
        
        ?>
    
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
     
        <?=$exceed_image_message ?>
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span> 

        </button>

      </div>
    
    
    <?php
        
    endif;
    ?>

    <h1>Welcome, <?php echo $current_user->data->display_name; ?></h1>


    <div class="row profile-row">

        <div class="col-3 side-bar">

    <?php
    if ($number_of_post > 0):
        ?>
                <div class="single-profile-item">

                    <a href="<?= site_url('member-create-post')?>" class="creat-post-member">Krairaj Secanje</a>

                </div>

    <?php
endif;
?>
            
            <div class="single-profile-item">

                <a href="<?= site_url()?>/update-member-profile" >Izmeni profil</a>

                </div>
            
            <div class="single-profile-item">

                <a href="<?php echo wp_logout_url(site_url()); ?>/membership-login">Izloguj se</a>

                </div>

        </div>

        <div class="col-8">

            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Naziv posta</th>
                        <th>Sadrzaj posta</th>
                        <th>Pogledaj post</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(!empty($all_post)):
                        
                        foreach($all_post->posts as $singlePost):
                        
                        $content  = strip_tags($singlePost->post_content);
                    
                        if(strlen($content)>200){
                            
                            $content = substr($content, 0, 100) . '...';
                        }
                    ?>
                    <tr>
                        <td><?=$singlePost->post_title?></td>
                        <td><?= $content?></td>
                        <td><a href="<?=get_permalink( $singlePost->ID )?>">Vidi post</a></td>
                        <td>
                           <a href="<?=site_url('/member-create-post/?id='.$singlePost->ID)?>" 
                              class="update-post-member" >Izmeni</a>| 
                               <a href="<?=site_url('/user-profile?action=delete')?>&post_id=<?=$singlePost->ID?>"  data-id="<?=$singlePost->ID?>" class="delete">Obrisi</a></td>
                    </tr>
                   <?php
                   
                         endforeach;
                   endif;
                   ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Ime</th>
                        <th>Sadrzaj</th>
                        <th>Akcija</th>
                    </tr>
                </tfoot>
            </table>

        </div>


    </div>

<div class="modal fade" id="create-post" tabindex="-1" role="dialog" aria-labelledby="create-post" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Krairaj secanje</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="post-body">

<?php
if ($all_post->post_count >= $number_of_post):


    echo "<p>Ne mozes da kreiras vise postova.</p>";

endif;
?>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Iskljuci</button>


            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="update-post" tabindex="-1" role="dialog" aria-labelledby="update-post" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Izmeni post</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="update-post-body">



            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Iskljuci</button>


            </div>

        </div>

    </div>

</div>

</div>


<script>
    $(document).ready(function () {
        
        $('.delete').on('click', function(e){
            
            e.preventDefault();
            
            if(confirm('da li ste sigurni da zelite da obrisete post?')){
                
                window.location.href= $(this).attr('href');
                
            }
            
        })
       
        
         $('.update-post-member').on('click', function () {


            var url_ajax = "<?= admin_url('admin-ajax.php'); ?>";

            jQuery.ajax({

                type: "post",

                url: url_ajax,

                data: {

                    action: "load_post_form",

                    number_of_posts: <?= $number_of_post ?>,

                    number_of_images:<?= $number_of_images ?>,
                    
                     post_id:$(this).attr('data-id')



                },

                success: function (response) {

                    $('#update-post-body').html(response)




                    new FroalaEditor('textarea');


                    $("#user-post-form").validate();
                }
            })


        })
    });


<?php
if ($all_post->post_count < $number_of_post):
    ?>
        $('.creat-post-member').on('click', function () {


            var url_ajax = "<?= admin_url('admin-ajax.php'); ?>";

            jQuery.ajax({

                type: "post",

                url: url_ajax,

                data: {

                    action: "load_post_form",

                    number_of_posts: <?= $number_of_post ?>,

                    number_of_images:<?= $number_of_images ?>



                },

                success: function (response) {

                    $('#post-body').html(response)




                    new FroalaEditor('textarea');


                    $("#user-post-form").validate();
                }
            })


        })

    <?php
endif;
?>
</script>

<script type="text/javascript">


  $(document).ready(function(){
      
      $.noConflict();
          
       $('#example').DataTable();
      
  })

</script>

<?php