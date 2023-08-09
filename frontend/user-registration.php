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

 if(is_user_logged_in()){
     
     ?>

<script type="text/javascript">

  window.location.href= "<?= site_url()?>/user-profile";

</script>
    
<?php
 }
        
$membership_name= get_option('membership_name');
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />




<div class="container user-membership">
    
  <?php
   if(!empty($_GET['action']) && $_GET['action'] == "payment"):
      
       include_once plguin_path.'/stripe/index.php';
       
       else:
           
       
        if(!empty($_GET['action']) && $_GET['action'] == "payment-process"):
            
            include_once plguin_path.'/stripe/process.php';
            
        endif;
  
?>  
  
  
    
    
    <form method="post" id="user-post-form">
        
        <?php
        
        if(!empty($_GET['action']) && $_GET['action'] =="done"):
        ?>
        
        <div id="notification-msg" class="alert alert-success alert-dismissible fade show" role="alert">
     
        You have successfully registered with us, stay tune you will notify once admin approve your account.
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
        
        <?php
        endif;
        ?>
        
         <?php
        
        if(!empty($_GET['action']) && $_GET['action'] =="failed"):
        ?>
        
        
        <div id="notification-msg" class="alert alert-danger alert-dismissible fade show" role="alert">
     
        Some error occured during paymen processing.
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
        
        <?php
        endif;
        ?>
        
        <input type="hidden" name="action" value="save_membership_users">
         
        <div class="form-group row">
            
        <label for="inputPassword" class="col-sm-3 col-form-label">Member email</label>

        
        <div class="col-sm-8">

            <input type="email" value="<?=$member_email?>" name="member_email" class="form-control" id="inputPassword" placeholder="Email" required="">

        </div>
        
        </div>
        
        <div class="form-group row">

        <label for="member_first_name" class="col-sm-3 col-form-label">First Name</label>

        <div class="col-sm-8">

            <input type="text" value="<?=$member_first_name?>" name="member_first_name" class="form-control" id="member_first_name" placeholder="First name" required="">

        </div>
        
        </div>
        
        <div class="form-group row"> 
            
        <label for="member_last_name"   class="col-sm-3 col-form-label">Last Name</label>

        <div class="col-sm-8">

            <input type="text" value="<?=$member_last_name?>"   name="member_last_name" class="form-control" id="member_last_name" placeholder="Last name" required="">

        </div>
        
        </div> 
        
         <div class="form-group row">
         <label for="member_phone"   class="col-sm-3 col-form-label">Phone</label>

        <div class="col-sm-8">

            <input type="text"  name="member_phone" class="form-control" id="member_phone" placeholder="Phone" required="">

        </div>
         </div>
         
         <div class="form-group row">
          <label for="member_invoice"   class="col-sm-3 col-form-label">Invoice</label>

        <div class="col-sm-8">

            <?php
             $number = "";
             for($i=0; $i<19; $i++) {
                    $min = ($i == 0) ? 1:0;
                    $number .= mt_rand($min,9);
                  }
            ?>
            <input type="text" readonly="" value="<?php echo $number;?>"  name="member_invoice" class="form-control" id="member_invoice" placeholder="Invoice">

        </div>
         </div>
        
        <div class="form-group row"> 
            
         <label for="member_password"  class="col-sm-3 col-form-label">Password</label>

        <div class="col-sm-8">

            <input type="password"  name="member_password"  minlength="8" class="form-control" id="member_password" placeholder="Password" required="">

        </div>
         
        </div>
        
        <div class="form-group row">
         
        <label for="member_password_confirm" class="col-sm-3 col-form-label">Confirm Password</label>

        <div class="col-sm-8">

            <input type="password" name="member_password_confirm" class="form-control" id="member_password_confirm" placeholder="Password" required="">

        </div>
        
        </div>
        
        <div class="form-group row">   
            
        <label for="member_level" class="col-sm-3 col-form-label">Payment</label>

        <div class="col-sm-8">

            <select class="form-select" name="member_payment" aria-label="Default select example" required="">
            
                <option value="">Select Payment Type</option>
                
                <option value="manual" <?php echo !empty($_POST['member_payment']) &&  $_POST['member_payment']=="manual"?"selected":''; ?>>Manual</option>
                
                <option value="direct" <?php echo !empty($_POST['member_payment']) &&  $_POST['member_payment']=="direct"?"selected":''; ?>>Direct</option>
                
                    

              </select>
        </div>
        
        </div>
        
        

        <div class="form-group row">   
            
        <label for="member_level" class="col-sm-3 col-form-label">Membership Level</label>

        <div class="col-sm-8">

            <select class="form-select" name="member_level" aria-label="Default select example" required="">
            
                <option value="">Select Membership Level </option>
                <?php
                
                  if(!empty($membership_name)):
                      
                      foreach($membership_name as $single):
                      
                      $selected = ($member_level == $single)?"selected":'';
                  
                       echo "<option $selected value='$single'>$single</option>";
                      
                      endforeach;
                      
                  endif;
                
                ?>
                    

              </select>
        </div>
        
        </div>
        
        
        
        
       
        
        
    <div class="col-12">
        
        <div class="error-regisration"></div>
      
          
    <div id="notification-msg" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
     
        You have successfully registered with us, stay tune you will notify once admin approve your account.
        
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            
          <span aria-hidden="true">&times;</span>

        </button>

      </div>
        
        <button class="btn btn-primary" type="submit">Register</button>
 
    </div>
        
</form>
    
    <?php
     endif;
    ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>

$("#user-post-form").validate({
    rules: {
                     
        member_password_confirm: {
            
            minlength: 8,
            
            equalTo: "#member_password"
        }
        
   
    },
         submitHandler : function(form) {
             
              var url_ajax = "<?= admin_url('admin-ajax.php'); ?>";
              
              $('.error-regisration').hide();
              
              $('.notification-msg').hide();
              
         $.ajax({
            
             url: url_ajax, 
            
            type: "POST",             
            
            data: $("#user-post-form").serialize(),
            
           
          success: function(data) {
              
              if(data=="error_email"){
                  
                  $('.error-regisration').fadeIn();
                  
                  $('.error-regisration').html('<p style="color:red">This email is already taken!</p>')
                 
                  return false;
              }
              
              if(data=="ok"){
                  
                  $('input[name="member_email"]').val('');
                  
                  $('input[name="member_first_name"]').val('');
                  
                  $('input[name="member_last_name"]').val('');
                  
                  $('input[name="member_password"]').val('');
                  
                  $('input[name="member_password_confirm"]').val('');
                  
                  $('#notification-msg').fadeIn();
                  
              }
              
               if(data=="direct"){
                  
                  window.location.href="<?=site_url()?>/user-membership?action=payment"
                  
              }
            }
        });
   
        return false;
    
    
   
}
    
});

</script>
<?php
