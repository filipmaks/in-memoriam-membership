<?php

if(!empty($_GET['action']) && $_GET['action']=="payment-process" && !empty($_POST['strip_token'])){
  
    require_once 'vendor/autoload.php';
  
    $token =  $_POST['strip_token'];
    
 $payment_type = get_option('payment_type');

$publish_key = get_option('stripe_test_publish_key');

$secret_key = get_option('stripe_test_secret_key');

if($payment_type ==1):
    
    $publish_key = get_option('stripe_live_publish_key');

    $secret_key = get_option('stripe_live_secret_key');
    
endif;

if(empty($publish_key)):
    
    $publish_key = "pk_test_6pRNASCoBOKtIshFeQd4XMUh";

     $secret_key =  'sk_test_ZKWeenHFhV14Mg2tuZXqWy7m';
    
endif;

    
    $stripe = array(
        
      "secret_key"      => $secret_key,
        
      "publishable_key" => $publish_key
        
    );    
	
    
    $token  = $_POST['strip_token'];
    /*
     * 
     * 
     * Client keys
     * 
     * pk_test_TYooMQauvdEDq54NiTphI7jx
     * 
     * sk_test_4eC39HqLyjWDarjtT1zdp7dc
     */
    
if(strpos($secret_key, "sk_test") !== false){
    
    $token = 'tok_visa';
    
}

    \Stripe\Stripe::setApiKey($stripe['secret_key']);    
    
	//add customer to stripe
    $customer = \Stripe\Customer::create(array(
        
		'name' => $_POST['customerName'],
	          
                 'description' => $_POST['item_details'],
        
                'email' => $_POST['emailAddress'],
        
                'source'  => "tok_visa",
 ));  
	
 
    // item details for which payment made
	$itemName = $_POST['item_details'];
        
        $bytes = random_bytes(5);
        
	$itemNumber = time().bin2hex($bytes);
        
	$itemPrice = $_POST['total_amount'] * 100;
        
	$totalAmount =  $_POST['total_amount'] * 100;
	
        $currency ='USD';
	
        $bytes = random_bytes(7);
        
        $orderNumber = time().bin2hex($bytes);   
    
    // details for which payment performed
    $payDetails = \Stripe\Charge::create(array(
        
        'customer' => $customer->id,
        
        'amount'   => $totalAmount,
        
        'currency' => $currency,
        
        'description' => $itemName,
        
        'metadata' => array(
       
            'order_id' => $orderNumber
        )
    ));   
    
    
    if(!empty($payDetails) && $payDetails->paid ==1):
        
         update_user_meta($_POST['member_user_id'], 'member_payment_status', 'completed');
    
      $user_data =  get_userdata($_SESSION['user_member_id']);
      
      $username = $user_data->data->display_name;
    
     $html ="<h2>Hello Dear</h2>, <br></br>";
              
              $html .="<p>The user '<strong>$username</strong>' has completed the payment.</p></br>";
              
                $to = get_option('admin_email');
                
                $subject = 'Payment Completed';
                
                $body = $html;
                
                $headers = array('Content-Type: text/html; charset=UTF-8');

                wp_mail( $to, $subject, $body, $headers );
    
        ?>
   
<script type="text/javascript">
   
  window.location.href ="<?php echo site_url()?>/user-membership/?action=done"

</script>
   <?php
        else:
            ?>
   
<script type="text/javascript">
   
     window.location.href ="<?php echo site_url()?>/user-membership/?action=failed"

</script>
   <?php
    endif;
    
} else{
         ?>
   
<script type="text/javascript">
   
     window.location.href ="<?php echo site_url()?>/user-membership/?action=failed"

</script>
   <?php
}
