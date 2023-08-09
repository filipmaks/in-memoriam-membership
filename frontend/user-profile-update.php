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



$member_email = "";

$member_first_name = "";

$member_last_name = "";

$member_level = "";

$email_exist_error =false;

$user_registration = false;


$current_user = wp_get_current_user();

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

if(!empty($_POST)):
    
    
    $member_first_name= sanitize_text_field( $_POST['member_first_name'] );
    
    $member_last_name = sanitize_text_field( $_POST['member_last_name'] );
    
    $member_password = trim($_POST['member_password']);
    
   
    if(!empty($member_password)):
        
         $user_data = wp_update_user( array( 
        
        'ID' => $_POST['member_id'], 
        
        'user_pass' => $member_password,
                
                'first_name' => $member_first_name,
                
                'last_name' => $member_last_name,
                
                'display_name' => "$member_first_name $member_last_name",
        ) );
    
        else:
        
             $user_data = wp_update_user( array( 
        
        'ID' => $_POST['member_id'], 
                
                'first_name' => $member_first_name,
                
                'last_name' => $member_last_name,
                
                'display_name' => "$member_first_name $member_last_name",
        ) );
    endif;
    
    update_user_meta($_POST['member_id'], 'member_date_of_death', $_POST['member_date_of_death']);
    
    update_user_meta($_POST['member_id'], 'member_date_of_birth', $_POST['member_date_of_birth']);
    
    update_user_meta($_POST['member_id'], 'member_phone', $_POST['member_phone']);
   
    update_user_meta($_POST['member_id'], 'member_invoice', $_POST['member_invoice']);
    
    update_user_meta($_POST['member_id'], 'member_invoice_call', $_POST['member_invoice_call']);
    
    update_user_meta($_POST['member_id'], 'member_price', $_POST['member_price']);
           
            
endif;



if(!empty($current_user->data->ID)):

$member_first_name = get_user_meta($current_user->data->ID, 'first_name', true);

$member_last_name = get_user_meta($current_user->data->ID, 'last_name', true);

$member_level = get_user_meta($current_user->data->ID, 'member_level', true);

endif;

$membership_name= get_option('membership_name');
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php
if(!is_user_logged_in()){
    
    ?>

<script type="text/javascript">

 window.location.href= "<?= site_url()?>";

</script>
<?php
}
?>
<div class="container user-membership">
    
    <?php
    if($email_exist_error):
        
        ?>
    
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
     
        User with this email already exist
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
    
    <?php
    endif;
    
    if($user_registration):
           ?>
    
    <div class="alert alert-success alert-dismissible fade show" role="alert">
     
        You have successfully registered with us, stay tune you will notify once admin approve your account.
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
    
    <?php
        
    endif;
    ?>
        
    <div class="row update-profile-row">
     
            
              <div class="col-3 side-bar">

    <?php
    if ($number_of_post > 0):
        ?>
                <div class="single-profile-item">

                    <a href="<?= site_url()?>/user-profile" class="creat-post-member" >Create Post</a>

                </div>

    <?php
endif;
?>
            
            <div class="single-profile-item">

                <a href="<?= site_url()?>/update-member-profile" >Update Profile</a>

                </div>
            
            <div class="single-profile-item">

                    <a href="<?php echo wp_logout_url(site_url()); ?>/membership-login">Logout</a>


                </div>

       
            
            
        </div>
        
      <div class="col-8">
    <form method="post" id="user-post-form">
         
        <div class="form-group row">
            
        <label for="member_first_name" class="col-sm-3 col-form-label">First Name</label>
        
        <input type="hidden" name="member_id" value="<?=$current_user->ID?>">

        <div class="col-sm-9">

            <input type="text" value="<?=$member_first_name?>" name="member_first_name" class="form-control" id="member_first_name" placeholder="First name" required="">

        </div>
        
        </div>
        
         <div class="form-group row">
             
        <label for="member_last_name"   class="col-sm-3 col-form-label">Last Name</label>

        <div class="col-sm-9">

            <input type="text" value="<?=$member_last_name?>"   name="member_last_name" class="form-control" id="member_last_name" placeholder="Last name" required="">

        </div>
        
         </div>
        
         <div class="form-group row">
         <label for="member_phone"   class="col-sm-3 col-form-label">Phone</label>

        <div class="col-sm-9">

            <input type="text" value="<?=get_user_meta($current_user->ID, 'member_phone', true)?>"   name="member_phone" class="form-control" id="member_phone" placeholder="Phone" required="">

        </div>
         </div>
         
         <div class="form-group row">
          <label for="member_invoice"   class="col-sm-3 col-form-label">Invoice</label>

        <div class="col-sm-9">

            <input type="text" readonly="" value="<?=get_user_meta($current_user->ID, 'member_invoice', true)?>"   name="member_invoice" class="form-control" id="member_invoice" placeholder="Invoice">

        </div>
         </div>
        
         <div class="form-group row">
           <label for="member_invoice_call"   class="col-sm-3 col-form-label">Invoice Call</label>

        <div class="col-sm-9">

            <input type="text" value="<?=get_user_meta($current_user->ID, 'member_invoice_call', true)?>"   name="member_invoice_call" class="form-control" id="member_invoice" placeholder="Invoice call">

        </div>
           
         </div>
        
         <div class="form-group row">
           
            <label for="member_price"   class="col-sm-3 col-form-label">Price</label>

        <div class="col-sm-9">

            <input type="text" value="<?=get_user_meta($current_user->ID, 'member_price', true)?>"   name="member_price" class="form-control" id="member_price" placeholder="Price">

        </div>
            
         </div>
        
        <?php
        
       $user_dod=  get_user_meta($current_user->ID, 'member_date_of_death', true);
    
       $user_dob=    get_user_meta($current_user->ID, 'member_date_of_birth', true);
        ?>
        
         <div class="form-group row">
        
         <label for="member_date_of_birth"   class="col-sm-3 col-form-label">Date Of Birth</label>

          <div class="col-sm-9">
        
                            <div class="input-group date" data-provide="datepicker">
                                
                                <input type="text" class="form-control" name="member_date_of_birth" value="<?=$user_dob?>" required="">
                        
                                <div class="input-group-addon">
                               
                                    <span class="glyphicon glyphicon-th"></span>
                        
                                </div>
                    
                            </div>
              
          </div>
         
         </div>
        
         <div class="form-group row">
          <label for="member_date_of_death"   class="col-sm-3 col-form-label">Date Of Death</label>

          <div class="col-sm-9">
        
                            <div class="input-group date" data-provide="datepicker">
                                
                                <input type="text" class="form-control" name="member_date_of_death" value="<?=$user_dod?>" required="">
                        
                                <div class="input-group-addon">
                               
                                    <span class="glyphicon glyphicon-th"></span>
                        
                                </div>
                    
                            </div>
              
          </div>
          
          
          
         </div>
         
         <div class="form-group row">
         <label for="member_password"  class="col-sm-3 col-form-label">Password</label>

        <div class="col-sm-9">

            <input type="password"  name="member_password"  minlength="8" class="form-control" id="member_password" placeholder="Password" >

        </div>
         
         </div>
        
        
      <div class="form-group row">   
        
          <label for="member_password"  class="col-sm-3 col-form-label">&nbsp;</label>
          
        <div class="col-9">
      
        <button class="btn btn-primary" type="submit">Submit form</button>
 
       </div>
       
      </div>
        
  </div>
        
    
        
</form>
          
          </div>
    </div>
    
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

$("#user-post-form").validate({});

</script>
<?php
