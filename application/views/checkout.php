
<?php
 $cpSecure ="https://secure.cinetpay.com";
$cpm_amount ="104";
$cpm_currency ="XOF";
$cpm_site_id ="551054";
$cpm_trans_id ="ECITY-20210318110921";
$cpm_trans_date ="2021-03-18 11:09:21";
$cpm_payment_config ="SINGLE";
$cpm_page_action ="PAYMENT";
$cpm_version ="V1";
$cpm_language ="fr";
$cpm_designation ="test_subscription";
$cpm_custom ="{'payment_by':'1','payment_to':'11','refrence_id':'12','payment_for':'1','Wallet':'0'}";
$apikey = "19031599135ff441c7b70865.48289010";
$signature ="2ab56ad22855f81913ccea9954b98cbab108054f55c063385a109911691ff7f48233";
$notify_url ="https://alphaxtech.net/ecity/index.php/web/notify/";
$return_url ="https://alphaxtech.net/ecity/index.php/web/notify/return/";
$cancel_url ="https://alphaxtech.net/ecity/index.php/web/notify/cancel/";
$cel_phone_num="0757563762";
$cpm_phone_prefixe ="225";

$debug ="1";


?>
<!DOCTYPE html>
<head>
    <title>Signature for payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <style>
        .container {
            width: 500px;
            clear: both;
        }

        .container input {
            width: 100%;
            clear: both;
            margin-bottom: 20px;
        }

        .container select {
            width: 100%;
            clear: both;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<form action="<?php echo $cpSecure; ?>" method="post" id="cinetPayForm">
    <input type="hidden" value="<?php echo $apikey; ?>" name="apikey">
    <input type="hidden" value="<?php echo $cpm_amount; ?>" name="cpm_amount">
    <input type="hidden" value="<?php echo $cpm_custom; ?>" name="cpm_custom">
    <input type="hidden" value="<?php echo $cpm_site_id; ?>" name="cpm_site_id">
    <input type="hidden" value="<?php echo $cpm_version; ?>" name="cpm_version">
    <input type="hidden" value="<?php echo $cpm_currency; ?>" name="cpm_currency">
    <input type="hidden" value="<?php echo $cpm_trans_id; ?>" name="cpm_trans_id">
    <input type="hidden" value="<?php echo $cpm_language; ?>" name="cpm_language">
    <input type="hidden" value="<?php echo $cpm_trans_date; ?>" name="cpm_trans_date">
    <input type="hidden" value="<?php echo $cpm_page_action; ?>" name="cpm_page_action">
    <input type="hidden" value="<?php echo $cpm_designation; ?>" name="cpm_designation">
    <input type="hidden" value="<?php echo $cpm_payment_config; ?>" name="cpm_payment_config">
    <input type="hidden" value="<?php echo $signature; ?>" name="signature">
    <input type="hidden" value="<?php echo $return_url; ?>" name="return_url">
    <input type="hidden" value="<?php echo $cancel_url; ?>" name="cancel_url">
    <input type="hidden" value="<?php echo $notify_url; ?>" name="notify_url">
    <input type="hidden" value="<?php echo $cel_phone_num; ?>" name="cel_phone_num">
    <input type="hidden" value="<?php echo $cpm_phone_prefixe; ?>" name="cpm_phone_prefixe">
 <input type="hidden" value="<?php echo $debug; ?>" name="debug"> 
    <input type="submit" value="Validate Payment">
</form>


<p>Hello</p>
</body>

<script>
    var cinetPayForm = document.getElementById('cinetPayForm');
    console.table(cinetPayForm);
</script>
</html>
