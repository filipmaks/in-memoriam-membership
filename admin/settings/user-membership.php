<?php
if (!empty($_POST)):


    update_option('membership_name', $_POST['membership_name']);

    update_option('membership_number_posts', $_POST['membership_number_posts']);

    update_option('membership_number_block_posts', $_POST['membership_number_block_posts']);

    update_option('membership_costs', $_POST['membership_costs']);

    update_option('memebership_status', $_POST['memebership_status']);
    
    update_option('membership_currency', $_POST['membership_currency']);
    
     update_option('currency_symbol', $_POST['currency_symbol']); 
            
    update_option('currency_name', $_POST['currency_name']); 
            

    if ($_POST['payment_type'] == 1):


        update_option('payment_type', $_POST['payment_type']);

        update_option('stripe_live_publish_key', $_POST['stripe_live_publish_key']);

        update_option('stripe_live_secret_key', $_POST['stripe_live_secret_key']);

    elseif ($_POST['payment_type'] == 2):

        update_option('payment_type', $_POST['payment_type']);

        update_option('stripe_test_publish_key', $_POST['stripe_test_publish_key']);

        update_option('stripe_test_secret_key', $_POST['stripe_test_secret_key']);

    endif;


endif;
?>

<style>

    .payment-type {
        display: inline-block;
        padding: 18px;
    }

    .field-delivery {
        display: inline-block;
        padding: 10px;
        width: 16%;
    }
    .field-delivery.delivery-option .days-field {
        width: auto;
        position: relative;
        top: -13px;
    }
    .delivery-label {
        display: inline-block;
        padding-right: 10px;
        vertical-align: text-top;
        width: 100%;
        position: relative;
        top: -11px;
    }
    .field-delivery.delivery-option .delivery-label {
        width: 69%;
    }
    .field-delivery.delivery-option .days-field {
        width: auto;
        position: relative; 
        top: -13px;
    }
    .days-field {
        display: inline-block;
        width: 53%;
    }

    .field-delivery.delivery-option .delivery-label {
        width: 18%;
        margin-top: -4px;
    }
    select.select-country,
    .days-field input{
        width: 100% !important;
    }
    .days-field .select2-container {
        float: left;
        width: 100% !important;
    }
    .field-delivery.vat-price .days-field {
        position: relative;
    }
    span.vat-percent {
        display: inline-block;
        right: -16px;
        position: ABSOLUTE;
        top: 6px;
    }
    .field-container {
        position: relative;
    }
    .field-container .add-more {
        position: absolute;
        right: 0;
        top: 13px;
        color: #135e96;
        cursor: pointer;
    }
    .field-container {
        position: relative;
        margin-top: 21px;
    }
    .field-container .remove {
        position: absolute;
        right: 0;
        top: 14px;
        color: red;
        cursor: pointer;
    }
    div#deliver_date {
        padding: 20px;
        margin-top: 100px;
    }
    #mainform div#deliver_date {
        display: block;
    }

    .field-delivery.delivery-option input[type="checkbox"] {
        width: auto !important;
    }
    .field-delivery.country-fld {
        width: 23%;
    }
</style>

<?php
$membership_name = get_option('membership_name');

$membership_number_posts = get_option('membership_number_posts');

$membership_number_block_posts = get_option('membership_number_block_posts');

$membership_costs = get_option('membership_costs');

$memebership_status = get_option('memebership_status');
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<form method="post">

    <div id="deliver_date" class="panel woocommerce_options_panel">

        <h1>Membership settings </h1>

<?php
if (!empty($membership_name)):

    foreach ($membership_name as $key_main => $single):
        ?>


                <div class="field-container">



                    <div class="field-delivery country-fld">

                        <div class="delivery-label">

                            Membership Name

                        </div>

                        <div class="days-field">

                            <input type="text" name="membership_name[]" value="<?= $single ?>">

                        </div>

                    </div>

                    <div class="field-delivery">

                        <div class="delivery-label">

                            Number of posts

                        </div>

                        <div class="days-field">

                            <input type="number"  name="membership_number_posts[]" value="<?= $membership_number_posts[$key_main] ?>">

                        </div>

                    </div>

                    <div class="field-delivery">

                        <div class="delivery-label">

                            Number of blocks per post

                        </div>

                        <div class="days-field">

                            <input type="number"  name="membership_number_block_posts[]" value="<?= $membership_number_block_posts[$key_main] ?>">

                        </div>

                    </div>


                    <div class="field-delivery">

                        <div class="delivery-label">

                            Cost

                        </div>

                        <div class="days-field">

                            <input type="number"  name="membership_costs[]" value="<?= $membership_costs[$key_main] ?>">

                        </div>

                    </div>

                    <div class="field-delivery delivery-option">

                        <div class="delivery-label">

                            Status

                        </div>

                        <div class="days-field">

                            <input type="hidden" name="memebership_status[]" class="membership_status"  value="<?= $memebership_status[$key_main] ?>">

                            <input type="checkbox" class="status_checkbox" <?= ($memebership_status[$key_main] == 1) ? "checked" : ''; ?>>

                        </div>

                    </div>



        <?php
        if ($key_main == 0):
            ?>
                        <div class="add-more">Add more</div>
            <?php
        else:
            ?>
                        <div class="remove">remove</div>
        <?php
        endif;
        ?>

                </div>


        <?php
    endforeach;

else:
    ?>


            <div class="field-container">



                <div class="field-delivery country-fld">

                    <div class="delivery-label">

                        Membership Name

                    </div>

                    <div class="days-field">

                        <input type="text" name="membership_name[]">

                    </div>

                </div>

                <div class="field-delivery">

                    <div class="delivery-label">

                        Number of posts

                    </div>

                    <div class="days-field">

                        <input type="number"  name="membership_number_posts[]">

                    </div>

                </div>

                <div class="field-delivery">

                    <div class="delivery-label">

                        Number of images per post

                    </div>

                    <div class="days-field">

                        <input type="number"  name="membership_number_posts_images[]">

                    </div>

                </div>


                <div class="field-delivery">

                    <div class="delivery-label">

                        Cost

                    </div>

                    <div class="days-field">

                        <input type="number"  name="membership_costs[]">

                    </div>

                </div>

                <div class="field-delivery delivery-option">

                    <div class="delivery-label">

                        Status

                    </div>

                    <div class="days-field">

                        <input type="hidden" name="memebership_status[]" class="membership_status" value="0">

                        <input type="checkbox" class="status_checkbox">

                    </div>

                </div>



                <div class="add-more">Add more</div>
            </div>

<?php
endif;
?>



    </div>

<?php
$payment_type = get_option('payment_type');

$stripe_live_publish_key = get_option('stripe_live_publish_key');

$stripe_live_secret_key = get_option('stripe_live_secret_key');

$stripe_test_publish_key = get_option('stripe_test_publish_key');

$stripe_test_secret_key = get_option('stripe_test_secret_key');
?>

    <div id="payment_settings" class="panel woocommerce_options_panel">

        <h1>Strip payment settings </h1>

        <div class="payment-type">

            <div class="radio"><input type="radio" <?= ($payment_type == 1) ? 'checked' : '' ?> value="1" name="payment_type">Live</div>


        </div>

        <div class="payment-type">

            <div class="radio"><input type="radio" value="2" 

    <?= ($payment_type == 2) ? 'checked' : '' ?>

    <?= empty($payment_type) ? 'checked' : '' ?>

                                      name="payment_type">Test</div>



        </div>


        <div class="live-container" style="display:<?= $payment_type == 1 ? 'block' : 'none' ?>;">

            <h2>Strip Live credentials </h2>

            <table class="form-table">

                <tr>

                    <th><label for="address"><?php _e("Publish Key"); ?></label></th>

                    <td>

                        <input type="text" name="stripe_live_publish_key" value="<?= $stripe_live_publish_key ?>" style="width:374px">
                    </td>

                </tr>

                <tr>

                    <th><label for="address"><?php _e("Secret Key"); ?></label></th>

                    <td>

                        <input type="text" name="stripe_live_secret_key"  value="<?= $stripe_live_secret_key ?>" style="width:374px">
                    </td>

                </tr>

            </table>

        </div>

<?php
$display = "none";

if (empty($payment_type)):

    $display = "block";

endif;
?>

        <div class="test-container" style="display:<?= $payment_type == 2 ? 'block' : $display ?>;">

            <h2>Strip Test credentials </h2>

            <table class="form-table">

                <tr>

                    <th><label for="address"><?php _e("Publish Key"); ?></label></th>

                    <td>

                        <input type="text" name="stripe_test_publish_key" value="<?= $stripe_test_publish_key ?>" style="width:374px">
                    </td>

                </tr>

                <tr>

                    <th><label for="address"><?php _e("Secret Key"); ?></label></th>

                    <td>

                        <input type="text" name="stripe_test_secret_key" value="<?= $stripe_test_secret_key ?>" style="width:374px">
                    </td>

                </tr>

            </table>

        </div>

<?php
// count 168
$currency_list = array(
    "AFA" => array("name" => "Afghan Afghani", "symbol" => "؋"),
    "ALL" => array("name" => "Albanian Lek", "symbol" => "Lek"),
    "DZD" => array("name" => "Algerian Dinar", "symbol" => "دج"),
    "AOA" => array("name" => "Angolan Kwanza", "symbol" => "Kz"),
    "ARS" => array("name" => "Argentine Peso", "symbol" => "$"),
    "AMD" => array("name" => "Armenian Dram", "symbol" => "֏"),
    "AWG" => array("name" => "Aruban Florin", "symbol" => "ƒ"),
    "AUD" => array("name" => "Australian Dollar", "symbol" => "$"),
    "AZN" => array("name" => "Azerbaijani Manat", "symbol" => "m"),
    "BSD" => array("name" => "Bahamian Dollar", "symbol" => "B$"),
    "BHD" => array("name" => "Bahraini Dinar", "symbol" => ".د.ب"),
    "BDT" => array("name" => "Bangladeshi Taka", "symbol" => "৳"),
    "BBD" => array("name" => "Barbadian Dollar", "symbol" => "Bds$"),
    "BYR" => array("name" => "Belarusian Ruble", "symbol" => "Br"),
    "BEF" => array("name" => "Belgian Franc", "symbol" => "fr"),
    "BZD" => array("name" => "Belize Dollar", "symbol" => "$"),
    "BMD" => array("name" => "Bermudan Dollar", "symbol" => "$"),
    "BTN" => array("name" => "Bhutanese Ngultrum", "symbol" => "Nu."),
    "BTC" => array("name" => "Bitcoin", "symbol" => "฿"),
    "BOB" => array("name" => "Bolivian Boliviano", "symbol" => "Bs."),
    "BAM" => array("name" => "Bosnia-Herzegovina Convertible Mark", "symbol" => "KM"),
    "BWP" => array("name" => "Botswanan Pula", "symbol" => "P"),
    "BRL" => array("name" => "Brazilian Real", "symbol" => "R$"),
    "GBP" => array("name" => "British Pound Sterling", "symbol" => "£"),
    "BND" => array("name" => "Brunei Dollar", "symbol" => "B$"),
    "BGN" => array("name" => "Bulgarian Lev", "symbol" => "Лв."),
    "BIF" => array("name" => "Burundian Franc", "symbol" => "FBu"),
    "KHR" => array("name" => "Cambodian Riel", "symbol" => "KHR"),
    "CAD" => array("name" => "Canadian Dollar", "symbol" => "$"),
    "CVE" => array("name" => "Cape Verdean Escudo", "symbol" => "$"),
    "KYD" => array("name" => "Cayman Islands Dollar", "symbol" => "$"),
    "XOF" => array("name" => "CFA Franc BCEAO", "symbol" => "CFA"),
    "XAF" => array("name" => "CFA Franc BEAC", "symbol" => "FCFA"),
    "XPF" => array("name" => "CFP Franc", "symbol" => "₣"),
    "CLP" => array("name" => "Chilean Peso", "symbol" => "$"),
    "CLF" => array("name" => "Chilean Unit of Account", "symbol" => "CLF"),
    "CNY" => array("name" => "Chinese Yuan", "symbol" => "¥"),
    "COP" => array("name" => "Colombian Peso", "symbol" => "$"),
    "KMF" => array("name" => "Comorian Franc", "symbol" => "CF"),
    "CDF" => array("name" => "Congolese Franc", "symbol" => "FC"),
    "CRC" => array("name" => "Costa Rican Colón", "symbol" => "₡"),
    "HRK" => array("name" => "Croatian Kuna", "symbol" => "kn"),
    "CUC" => array("name" => "Cuban Convertible Peso", "symbol" => "$, CUC"),
    "CZK" => array("name" => "Czech Republic Koruna", "symbol" => "Kč"),
    "DKK" => array("name" => "Danish Krone", "symbol" => "Kr."),
    "DJF" => array("name" => "Djiboutian Franc", "symbol" => "Fdj"),
    "DOP" => array("name" => "Dominican Peso", "symbol" => "$"),
    "XCD" => array("name" => "East Caribbean Dollar", "symbol" => "$"),
    "EGP" => array("name" => "Egyptian Pound", "symbol" => "ج.م"),
    "ERN" => array("name" => "Eritrean Nakfa", "symbol" => "Nfk"),
    "EEK" => array("name" => "Estonian Kroon", "symbol" => "kr"),
    "ETB" => array("name" => "Ethiopian Birr", "symbol" => "Nkf"),
    "EUR" => array("name" => "Euro", "symbol" => "€"),
    "FKP" => array("name" => "Falkland Islands Pound", "symbol" => "£"),
    "FJD" => array("name" => "Fijian Dollar", "symbol" => "FJ$"),
    "GMD" => array("name" => "Gambian Dalasi", "symbol" => "D"),
    "GEL" => array("name" => "Georgian Lari", "symbol" => "ლ"),
    "DEM" => array("name" => "German Mark", "symbol" => "DM"),
    "GHS" => array("name" => "Ghanaian Cedi", "symbol" => "GH₵"),
    "GIP" => array("name" => "Gibraltar Pound", "symbol" => "£"),
    "GRD" => array("name" => "Greek Drachma", "symbol" => "₯, Δρχ, Δρ"),
    "GTQ" => array("name" => "Guatemalan Quetzal", "symbol" => "Q"),
    "GNF" => array("name" => "Guinean Franc", "symbol" => "FG"),
    "GYD" => array("name" => "Guyanaese Dollar", "symbol" => "$"),
    "HTG" => array("name" => "Haitian Gourde", "symbol" => "G"),
    "HNL" => array("name" => "Honduran Lempira", "symbol" => "L"),
    "HKD" => array("name" => "Hong Kong Dollar", "symbol" => "$"),
    "HUF" => array("name" => "Hungarian Forint", "symbol" => "Ft"),
    "ISK" => array("name" => "Icelandic Króna", "symbol" => "kr"),
    "INR" => array("name" => "Indian Rupee", "symbol" => "₹"),
    "IDR" => array("name" => "Indonesian Rupiah", "symbol" => "Rp"),
    "IRR" => array("name" => "Iranian Rial", "symbol" => "﷼"),
    "IQD" => array("name" => "Iraqi Dinar", "symbol" => "د.ع"),
    "ILS" => array("name" => "Israeli New Sheqel", "symbol" => "₪"),
    "ITL" => array("name" => "Italian Lira", "symbol" => "L,£"),
    "JMD" => array("name" => "Jamaican Dollar", "symbol" => "J$"),
    "JPY" => array("name" => "Japanese Yen", "symbol" => "¥"),
    "JOD" => array("name" => "Jordanian Dinar", "symbol" => "ا.د"),
    "KZT" => array("name" => "Kazakhstani Tenge", "symbol" => "лв"),
    "KES" => array("name" => "Kenyan Shilling", "symbol" => "KSh"),
    "KWD" => array("name" => "Kuwaiti Dinar", "symbol" => "ك.د"),
    "KGS" => array("name" => "Kyrgystani Som", "symbol" => "лв"),
    "LAK" => array("name" => "Laotian Kip", "symbol" => "₭"),
    "LVL" => array("name" => "Latvian Lats", "symbol" => "Ls"),
    "LBP" => array("name" => "Lebanese Pound", "symbol" => "£"),
    "LSL" => array("name" => "Lesotho Loti", "symbol" => "L"),
    "LRD" => array("name" => "Liberian Dollar", "symbol" => "$"),
    "LYD" => array("name" => "Libyan Dinar", "symbol" => "د.ل"),
    "LTC" => array("name" => "Litecoin", "symbol" => "Ł"),
    "LTL" => array("name" => "Lithuanian Litas", "symbol" => "Lt"),
    "MOP" => array("name" => "Macanese Pataca", "symbol" => "$"),
    "MKD" => array("name" => "Macedonian Denar", "symbol" => "ден"),
    "MGA" => array("name" => "Malagasy Ariary", "symbol" => "Ar"),
    "MWK" => array("name" => "Malawian Kwacha", "symbol" => "MK"),
    "MYR" => array("name" => "Malaysian Ringgit", "symbol" => "RM"),
    "MVR" => array("name" => "Maldivian Rufiyaa", "symbol" => "Rf"),
    "MRO" => array("name" => "Mauritanian Ouguiya", "symbol" => "MRU"),
    "MUR" => array("name" => "Mauritian Rupee", "symbol" => "₨"),
    "MXN" => array("name" => "Mexican Peso", "symbol" => "$"),
    "MDL" => array("name" => "Moldovan Leu", "symbol" => "L"),
    "MNT" => array("name" => "Mongolian Tugrik", "symbol" => "₮"),
    "MAD" => array("name" => "Moroccan Dirham", "symbol" => "MAD"),
    "MZM" => array("name" => "Mozambican Metical", "symbol" => "MT"),
    "MMK" => array("name" => "Myanmar Kyat", "symbol" => "K"),
    "NAD" => array("name" => "Namibian Dollar", "symbol" => "$"),
    "NPR" => array("name" => "Nepalese Rupee", "symbol" => "₨"),
    "ANG" => array("name" => "Netherlands Antillean Guilder", "symbol" => "ƒ"),
    "TWD" => array("name" => "New Taiwan Dollar", "symbol" => "$"),
    "NZD" => array("name" => "New Zealand Dollar", "symbol" => "$"),
    "NIO" => array("name" => "Nicaraguan Córdoba", "symbol" => "C$"),
    "NGN" => array("name" => "Nigerian Naira", "symbol" => "₦"),
    "KPW" => array("name" => "North Korean Won", "symbol" => "₩"),
    "NOK" => array("name" => "Norwegian Krone", "symbol" => "kr"),
    "OMR" => array("name" => "Omani Rial", "symbol" => ".ع.ر"),
    "PKR" => array("name" => "Pakistani Rupee", "symbol" => "₨"),
    "PAB" => array("name" => "Panamanian Balboa", "symbol" => "B/."),
    "PGK" => array("name" => "Papua New Guinean Kina", "symbol" => "K"),
    "PYG" => array("name" => "Paraguayan Guarani", "symbol" => "₲"),
    "PEN" => array("name" => "Peruvian Nuevo Sol", "symbol" => "S/."),
    "PHP" => array("name" => "Philippine Peso", "symbol" => "₱"),
    "PLN" => array("name" => "Polish Zloty", "symbol" => "zł"),
    "QAR" => array("name" => "Qatari Rial", "symbol" => "ق.ر"),
    "RON" => array("name" => "Romanian Leu", "symbol" => "lei"),
    "RUB" => array("name" => "Russian Ruble", "symbol" => "₽"),
    "RWF" => array("name" => "Rwandan Franc", "symbol" => "FRw"),
    "SVC" => array("name" => "Salvadoran Colón", "symbol" => "₡"),
    "WST" => array("name" => "Samoan Tala", "symbol" => "SAT"),
    "STD" => array("name" => "São Tomé and Príncipe Dobra", "symbol" => "Db"),
    "SAR" => array("name" => "Saudi Riyal", "symbol" => "﷼"),
    "RSD" => array("name" => "Serbian Dinar", "symbol" => "din"),
    "SCR" => array("name" => "Seychellois Rupee", "symbol" => "SRe"),
    "SLL" => array("name" => "Sierra Leonean Leone", "symbol" => "Le"),
    "SGD" => array("name" => "Singapore Dollar", "symbol" => "$"),
    "SKK" => array("name" => "Slovak Koruna", "symbol" => "Sk"),
    "SBD" => array("name" => "Solomon Islands Dollar", "symbol" => "Si$"),
    "SOS" => array("name" => "Somali Shilling", "symbol" => "Sh.so."),
    "ZAR" => array("name" => "South African Rand", "symbol" => "R"),
    "KRW" => array("name" => "South Korean Won", "symbol" => "₩"),
    "SSP" => array("name" => "South Sudanese Pound", "symbol" => "£"),
    "XDR" => array("name" => "Special Drawing Rights", "symbol" => "SDR"),
    "LKR" => array("name" => "Sri Lankan Rupee", "symbol" => "Rs"),
    "SHP" => array("name" => "St. Helena Pound", "symbol" => "£"),
    "SDG" => array("name" => "Sudanese Pound", "symbol" => ".س.ج"),
    "SRD" => array("name" => "Surinamese Dollar", "symbol" => "$"),
    "SZL" => array("name" => "Swazi Lilangeni", "symbol" => "E"),
    "SEK" => array("name" => "Swedish Krona", "symbol" => "kr"),
    "CHF" => array("name" => "Swiss Franc", "symbol" => "CHf"),
    "SYP" => array("name" => "Syrian Pound", "symbol" => "LS"),
    "TJS" => array("name" => "Tajikistani Somoni", "symbol" => "SM"),
    "TZS" => array("name" => "Tanzanian Shilling", "symbol" => "TSh"),
    "THB" => array("name" => "Thai Baht", "symbol" => "฿"),
    "TOP" => array("name" => "Tongan Pa'anga", "symbol" => "$"),
    "TTD" => array("name" => "Trinidad & Tobago Dollar", "symbol" => "$"),
    "TND" => array("name" => "Tunisian Dinar", "symbol" => "ت.د"),
    "TRY" => array("name" => "Turkish Lira", "symbol" => "₺"),
    "TMT" => array("name" => "Turkmenistani Manat", "symbol" => "T"),
    "UGX" => array("name" => "Ugandan Shilling", "symbol" => "USh"),
    "UAH" => array("name" => "Ukrainian Hryvnia", "symbol" => "₴"),
    "AED" => array("name" => "United Arab Emirates Dirham", "symbol" => "إ.د"),
    "UYU" => array("name" => "Uruguayan Peso", "symbol" => "$"),
    "USD" => array("name" => "US Dollar", "symbol" => "$"),
    "UZS" => array("name" => "Uzbekistan Som", "symbol" => "лв"),
    "VUV" => array("name" => "Vanuatu Vatu", "symbol" => "VT"),
    "VEF" => array("name" => "Venezuelan BolÃvar", "symbol" => "Bs"),
    "VND" => array("name" => "Vietnamese Dong", "symbol" => "₫"),
    "YER" => array("name" => "Yemeni Rial", "symbol" => "﷼"),
    "ZMK" => array("name" => "Zambian Kwacha", "symbol" => "ZK"),
    "ZWL" => array("name" => "Zimbabwean dollar", "symbol" => "$")
);
?>

        <div class="currency-container">

            <h1>Currency </h1>
             <table class="form-table">

                <tr>

                    <th><label for="address"><?php _e("Currency"); ?></label></th>

                    <td class="currency-container">
                        
                        <?php
                        $currency_name = get_option('currency_name');
                
                        $membership_currency = get_option('membership_currency');
                        
                        $currency_symbol = get_option('currency_symbol');
                
                        ?>

                        <input type="hidden" name="currency_name" id="currency_name" value="<?=$currency_name?>">
            
            <select class="form-select" id="currency" name="membership_currency">
                <option>select currency</option>
                <?php
                
               foreach($currency_list as $key=>$single):
                       
                   $selected='';
               
                      if($key == $membership_currency):
                          
                          $selected ="selected";
                          
                      endif;
                      ?>
                <option <?=$selected?> value="<?=$key?>" data-symbole="<?=$single['symbol']?>"><?=$single['name']?></option>
                    <?php
                   endforeach; 
                ?>
                
            </select>
                        <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=$currency_symbol?>">
                   
                    </td>

                </tr>
                
             </table>
           
        </div>

    </div>

    <p class="submit">

        <button name="save" class="button-primary woocommerce-save-button" type="submit" value="Save">Save</button>

    </p>
</from>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

$('#currency').on('change', function(){
    
    var selectedOptionText = $("#currency option:selected").text();
    
    var c_sybmoble = $("#currency option:selected").attr('data-symbole');
    
    $(this).parent('.currency-container').find('#currency_name').val(selectedOptionText);
    
    $(this).parent('.currency-container').find('#currency_symbol').val(c_sybmoble)
    
})
$('#currency').select2();

        $('input[name="payment_type"]').on('click', function () {



            var thisVal = $(this).val();


            if (thisVal == 1) {

                $('.live-container').fadeIn();

                $('.test-container').fadeOut();

            } else {

                $('.live-container').fadeOut();

                $('.test-container').fadeIn();

            }
        })

        $(document).on('click', '.status_checkbox', function () {

            if ($(this).prop('checked') == true) {

                $(this).prev('.membership_status').val(1);

            } else {

                $(this).prev('.membership_status').val(0);

            }

        })


        $('.add-more').on('click', function () {




            var select_option = '';



            var html = ' <div class="field-container"><div class="field-delivery country-fld"> <div class="delivery-label">   Membership Name </div>\n\
        <div class="days-field"> \n\
  <input type="text" name="membership_name[]">\n\
</div>   </div>';

            html += '<div class="field-delivery"> <div class="delivery-label">  Number of posts  </div>  \n\
     <div class="days-field">  <input type="number"  name="membership_number_posts[]"></div> \n\
</div> ';

            html += '<div class="field-delivery"> <div class="delivery-label">  Number of images per posts  </div>  \n\
     <div class="days-field"><input type="number"  name="membership_number_posts_images[]"></div> \n\
</div> ';


            html += '<div class="field-delivery vat-price"> <div class="delivery-label">  Cost  </div>  \n\
     <div class="days-field">  <input type="number"  name="membership_costs[]"> <span class="vat-percent"></span></div> \n\
</div> ';
            html += '<div class="field-delivery delivery-option"> <div class="delivery-label">  Status </div>  \n\
     <div class="days-field"><input type="hidden" name="memebership_status[]" class="membership_status" value="0">  <input type="checkbox" class="status_checkbox"></div> \n\
</div> <div class="remove">remove</div> </div> </div>';




            $(this).parent('.field-container').parent().append(html);

        })

        $(document).on('click', '.remove', function () {

            if (confirm('Are you sure you want to remove it?')) {



                $(this).parent('.field-container').remove();


            }

        })

    });
</script>