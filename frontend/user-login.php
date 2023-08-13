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

$email_exist_error =false;

$user_registration = false;
 if(is_user_logged_in()){
     
     ?>

<script type="text/javascript">

  window.location.href= "<?= site_url()?>/user-profile";

</script>
    
<?php
 }
   

?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


<div class="container user-membership">
    
    <?php
    
    if(!empty($_SESSION['login_error'])):
        
         ?>
    
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
     
        <?=$_SESSION['login_error']?>
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
    <?php
    
    endif;
    
    
    ?>
        
    <form method="post" id="user-login-form">
        
        <h1>Membership Login </h1>
         
        <div class="form-group row">
            
        <label for="inputPassword" class="col-sm-2 col-form-label">Email</label>

        <div class="col-sm-10">

            <input type="email" value="<?=$member_email?>" name="member_login_email" class="form-control" id="inputPassword" placeholder="Email" required="">

        </div>
        
        </div>
        
        <div class="form-group row">   
            
            <label for="member_password"  class="col-sm-2 col-form-label">Password</label>

        <div class="col-sm-10">

            <input type="password"  name="member_password"  class="form-control" id="member_password" placeholder="Password" required="">

        </div>
        
        
        
       
        
  </div>
        
    <div class="col-12">
      
        <button class="btn btn-primary" type="submit">Login</button>
        
        <p>If you dont have account, <a href="<?= site_url()?>/user-membership">please register</a></p>
 
       <a class="reset-password-url" href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e( 'Lost Password', 'textdomain' ); ?>">
	<?php esc_html_e( 'Lost Password', 'textdomain' ); ?>
</a>

    </div>
        
</form>
    
</div>

<script>

$("#user-login-form").validate();

</script>
<?php
