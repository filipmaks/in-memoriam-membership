
<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-creditcardvalidator/1.0.0/jquery.creditCardValidator.js"></script>-->
<!--<script type="text/javascript" src="<?= plugins_url ?>/stripe/script/payment.js"></script>-->

<style >
    .container.payment-form-container {
        max-width: 500px !important;
    }
    * {
        font-family: "Helvetica Neue", Helvetica;
        font-size: 15px;
        font-variant: normal;
        padding: 0;
        margin: 0;
    }

    html {
        height: 100%;
    }

    body {
        background: #E6EBF1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
    }

    form {
        width: 480px;
        margin: 20px 0;
        display: inline-block;
    }

    .group {
        background: white;
        box-shadow: 0 7px 14px 0 rgba(49,49,93,0.10),
            0 3px 6px 0 rgba(0,0,0,0.08);
        border-radius: 4px;
        margin-bottom: 20px;
    }

    label {
        position: relative;
        color: #8898AA;
        font-weight: 300;
        height: 40px;
        line-height: 40px;
        margin-left: 20px;
        display: flex;
        flex-direction: row;
    }

    .group label:not(:last-child) {
        border-bottom: 1px solid #F0F5FA;
    }

    label > span {
        width: 80px;
        text-align: right;
        margin-right: 30px;
    }

    .field {
        background: transparent;
        font-weight: 300;
        border: 0;
        color: #31325F;
        outline: none;
        flex: 1;
        padding-right: 10px;
        padding-left: 10px;
        cursor: text;
    }

    .field::-webkit-input-placeholder { color: #CFD7E0; }
    .field::-moz-placeholder { color: #CFD7E0; }

    button {
        float: left;
        display: block;
        background: #666EE8;
        color: white;
        box-shadow: 0 7px 14px 0 rgba(49,49,93,0.10),
            0 3px 6px 0 rgba(0,0,0,0.08);
        border-radius: 4px;
        border: 0;
        margin-top: 20px;
        font-size: 15px;
        font-weight: 400;
        width: 100%;
        height: 40px;
        line-height: 38px;
        outline: none;
    }

    button:focus {
        background: #555ABF;
    }

    button:active {
        background: #43458B;
    }

    .outcome {
        float: left;
        width: 100%;
        padding-top: 8px;
        min-height: 24px;
        text-align: center;
    }

    .success, .error {
        display: none;
        font-size: 13px;
    }

    .success.visible, .error.visible {
        display: inline;
    }

    .error {
        color: #E4584C;
    }

    .success {
        color: #666EE8;
    }

    .success .token {
        font-weight: 500;
        font-size: 13px;
    }

 
</style>
<div class="container  payment-form-container">	
    <div class="row">	
        <h2>Please fill all the required field</h2>	

        <?php
        $user_level = get_user_meta($_COOKIE['user_member_id'], 'member_level', true);

        $membership_name = get_option('membership_name');

        $membership_costs = get_option('membership_costs');

        $cost = 0;

        $package_name = $user_level;

        if (!empty($membership_name)):

            foreach ($membership_name as $key => $single):


                if ($single == $user_level):

                    $cost = !empty($membership_costs[$key]) ? $membership_costs[$key] : 0;

                endif;

            endforeach;

        endif;



        $user_meta = get_userdata($_COOKIE['user_member_id']);

        $first_name = get_user_meta($_COOKIE['user_member_id'], 'first_name', true);

        $last_name = get_user_meta($_COOKIE['user_member_id'], 'last_name', true);

?>
<?php
                
               // $membership_currency = get_option('membership_currency');
                
                 $membership_currency = get_option('currency_symbol');
                ?>
        
        <div class="panel panel-default">	
            
            <div class="panel-body">

                <script src="https://js.stripe.com/v3/"></script>

                <form id="strip-form">
                    
                    <div class="group">
                    
                        <label>
                        
                            <span>Name</span>
                            
                            <input id="name" class="field" placeholder="Jane Doe" />
                        
                        </label>
                        
                        <label>
                        
                            <span>Card</span>
                            
                            <div id="card-element" class="field"></div>
                        
                        </label>
                   
                    </div>			
                    
                    <button type="submit">Pay <?=!empty($membership_currency)?$membership_currency:'$'?><?= $cost ?></button>
                    
                    <div class="outcome">
                    
                        <input name="strip_token" value="" type="hidden">

                        <div class="error"></div>
                        
                        <div class="success">
                        
                            Success! Your Stripe token is <span class="token"></span>
                        
                        
                        </div>
                    
                    </div>
                
                </form>

                
                

                <form action="<?= site_url() ?>/user-membership/?action=payment-process" method="POST" id="paymentForm">	
                   
                    <div class="row">
                    
                        <div class="col-md-12">

                            <div align="center">
                                
                                <input name="strip_token" value="" type="hidden">

                                <input type="hidden" name="emailAddress" value="<?= $user_meta->data->user_email ?>">

                                <input type="hidden" name="customerName" value="<?= $first_name . ' ' . $last_name ?>">

                                <input type="hidden" name="price" value="<?= $cost ?>">
                                
                                <input type="hidden" name="total_amount" value="<?= $cost ?>">
                                
                                <input type="hidden" name="currency_code" value="<?=!empty($membership_currency)?$membership_currency:'USD'?>">
                                
                                <input type="hidden" name="item_details" value="<?= $package_name ?>">
                                
                                <input type="hidden" name="item_number" value="<?= $package_name ?>">
                                
                                <input type="hidden" name="order_number" value="<?= $package_name ?>">
                                
                                <input type="hidden" name="member_user_id" value="<?=$_COOKIE['user_member_id']?>">
                           
                            </div>
                            
                        </div>

                    </div>

                </form>

            </div>
       
        </div>	

    </div>		
    
</div>

<?php

$payment_type = get_option('payment_type');

$publish_key = get_option('stripe_test_publish_key');

if($payment_type ==1):
    
    $publish_key = get_option('stripe_live_publish_key');
    
endif;

if(empty($publish_key)):
    
   echo "test key". $publish_key = "pk_test_6pRNASCoBOKtIshFeQd4XMUh";
    
endif;

?>


<script type="text/javascript">


    var stripe = Stripe('<?= $publish_key ?>');
    
    var elements = stripe.elements();

    var card = elements.create('card', {
    
        style: {
        
            base: {
            
                iconColor: '#666EE8',
                
                color: '#31325F',
                
                lineHeight: '40px',
                
                fontWeight: 300,
                
                fontFamily: 'Helvetica Neue',
                
                fontSize: '15px',

                '::placeholder': {
                    
                    color: '#CFD7E0',
                },
            },
        }
    });
    
    card.mount('#card-element');

    $('#name').attr('name', 'card_holder_name')

    $('input[name="card_holder_name"]').val("<?= $first_name . ' ' . $last_name ?>")

    function setOutcome(result) {
    
        var successElement = document.querySelector('.success');
        
        var errorElement = document.querySelector('.error');
        
        successElement.classList.remove('visible');
        
        errorElement.classList.remove('visible');

        if (result.token) {
// Use the token to create a charge or a customer
// https://stripe.com/docs/charges

            $('input[name="strip_token"]').val(result.token.id)


              $('#paymentForm').submit();

           // successElement.querySelector('.token').textContent = result.token.id;
           // successElement.classList.add('visible');
        } else if (result.error) {
            
            errorElement.textContent = result.error.message;
            
            errorElement.classList.add('visible');
        }
    }

    card.on('change', function (event) {
        
        setOutcome(event);
    
    });
    

    document.querySelector('form').addEventListener('submit', function (e) {
        
        e.preventDefault();
        
        var name = document.getElementById('name').value;
        
        if (!name) {
        
            var errorElement = document.querySelector('.error');
            
            errorElement.textContent = "You must enter a name.";
            
            errorElement.classList.add('visible');
            
            return;
        }
        
        var options = {
        
            name: name,
        
        };
        
        stripe.createToken(card, options).then(setOutcome);
    
    });

</script>