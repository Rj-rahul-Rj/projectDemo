
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
            integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
            crossorigin="anonymous"
        />
        <style>
            body {
                font-family: Arial;
                font-size: 17px;
                padding: 8px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            input[type="text"] {
                width: 100%;
                margin-bottom: 20px;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 3px;
            }

            label {
                margin-bottom: 10px;
                /*display: block;*/
                display: none;
            }

            .icon-container {
                margin-bottom: 20px;
                padding: 7px 0;
                font-size: 24px;
            }

            .btn {
                background-color: #4caf50;
                color: white;
                padding: 12px;
                margin: 10px 0;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                font-size: 17px;
            }

            .btn:hover {
                background-color: #45a049;
            }

            a {
                color: #2196f3;
            }

            hr {
                border: 1px solid lightgrey;
            }
            label {
                text-transform: capitalize;
                font-size: 16px;
                margin: 10px 0;
            }
            span.price {
                float: right;
                color: grey;
            }
            .card {
                border: none;
                padding: 30px;
                /* padding: 5px 20px 15px 20px;*/
                border: 1px solid lightgrey;
                border-radius: 3px;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                transition: 0.3s;
            }
            h3 {
                color: #4caf50;
                font-size: 28px;
                font-weight: bold;
            }
            @media (max-width: 800px) {
                .row {
                    flex-direction: column-reverse;
                }
                .col-25 {
                    margin-bottom: 20px;
                }
            }

            /*button*/
            .button3 {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

h3 {
    text-align: center;
   width: 100%; 
}
h3 {
  font-weight: bold;
}

.button3 {width: 100%;}
        </style>
    </head>
    <body>
        <div class="my-2">
            <div class="container" id="">
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div id="form-1-div" class="card">
                            <span class="formError text-danger"></span>
                                <form id="payment_form" name="myform" method="post">
                                <h3>Confirm Payment</h3>
                                <img width="100%"; height="auto" src="<?php echo base_url().'default/loader.gif';?>">

                                <!-- <===============PAY-WITH-Mobile===========> -->
                                <div class="">
                                    <label class="amountData" style="display: none;">Amount</label>
                                    <input class="form-control" type="hidden" name="cpm_amount" placeholder="enter amount in numbers" value="<?php echo $amount?>" />
                                    <span id="cpm_amount_err" class="text-danger"></span>
                                    <label for="cpm_currency">Currency</label>
                                     <input class="form-control" type="hidden" name="cpm_currency" value="<?php echo $currency; ?>"/>
                                    <!-- by phone payment -->
                                    <label class="phoneConfig">Market</label>
                                    <input class="form-control" type="hidden" name="cpm_Market" value="<?php echo $market; ?>" />

                                    <label>Operator</label>
                                    <input class="form-control" type="hidden" name="cpm_Operator" value="<?php echo $operator; ?>" />

                                    <label >phone</label>
                                    <input  type="hidden" name="cel_phone_num" value="<?php echo $phone; ?>"  />
                                   
                                    <label>phone_prefixe</label>
                                    <input  type="hidden" name="cpm_phone_prefixe"  value="<?php echo $country_code; ?>" />
                                    <!-- by phone payment -->

                                   
                                    <!-- by card payment -->

                                    <label style="display: none;">Description Transaction</label>
                                    <input type="hidden" name="cpm_designation" value="payment description" />
                                    <span id="cpm_designation_err" class="text-danger"></span>
                                    <input
                                        type="hidden"
                                        name="cpm_custom"
                                        id="cpm_custom"
                                        value="{'payment_by':'<?php echo $payment_by; ?>','payment_to':'<?php echo $payment_to; ?>','payment_mode':'<?php echo $payment_mode; ?>','payment_for':'<?php echo $payment_for; ?>','reference_id':'<?php echo $reference_id; ?>','cpm_trans_id':'<?php echo $cpm_trans_id; ?>','Wallet':'0','commission':'0','orginal_amount':'0','fee':'0','margin':'0','market':'','operator':''}"/>

                                    <input type="hidden" name="payment_mode" class="payment_mode" value="<?php echo $payment_mode; ?>" />
                                    <input type="hidden" name="cpm_trans_id" class="cpm_trans_id" value="<?php echo $cpm_trans_id; ?>" />
                                    <input type="hidden" name="market_val" id="market_val" value="<?php echo $market;?>">
                                    <input type="hidden" name="operator_val" id="operator_val" value="<?php echo $operator; ?>">
                                    <input type="hidden" name="debug" value="0" />

                                 <!--    <p class="text-center mt-3">
                                        <button style="color: green;" type="submit" class="cinetpay-button large form-control">Proceed for payment</button>
                                    </p>  -->
                                    <p class="text-center mt-3">
                                        <button style="color: green;" type="submit" class="text-warning button3">Confirm your payment</button>
                                    </p>
                                </div>
                            </form>
                        </div>
                        <!-- first form ends -->
                        <!-- second form starts -->
                        <div id="confirm-form-div" class="card">

                        </div>
                        <!-- second form ends -->
                    </div>
                </div>
            </div>
        </div>

        <!-- <=====================PAY-MOBILE=============> -->
  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    </body>
</html>

<script>
    $(document).ready(function () {
        
        $('#confirm-form-div').hide();
        $( "#payment_form" ).one( "submit" );
        // $("#payment_form").submit(function (e) {
            $('.text-danger').text('');
            // e.preventDefault();
            var fd = new FormData(document.getElementById("payment_form"));
            $.ajax({
                type: "POST",
                url: '<?= base_url("index.php/web/CinetPay_wallet/addPayment"); ?>',
                data: fd,
                processData: false, // tell jQuery not to process the data
                contentType: false,
                success: function (response) {
                    // alert(response);
                    console.log(response);
                    var res = JSON.parse(response);

                    if (res.status == false) {

                        $('#confirm-form-div').hide();
                        if(typeof(res.message) !='string'){

                        $.each(res.message, function(key, value) {
                                console.log(key); 
                                console.log(value);
                                $('#'+key+'_err').text(value);
                        });
                        }
                        else
                        {
                            $('.formError').text(res.message);
                        }

                    } else if (res.status == true) {
                        var formdata = res.form;
                        $('#form-1-div').hide();
                        $('#confirm-form-div').show();
                        $('#confirm-form-div').html(formdata);
                        toastr.success(res.msg);
                        $("#payment_form")[0].reset();
                        //$('#out_list').DataTable().ajax.reload();
                    }
                },
            });
        // });
    });
</script>

<!-- <script type="text/javascript">document.myform.submit();</script>
 -->
