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
                display: block;
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
        </style>
    </head>
    <body>
        <div class="my-2">
            <div class="container" id="">
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div id="form-1-div" class="card">
                            <span class="formError text-danger"></span>
                            <form id="payment_form" name="myForm" class="form-group" method="post">
                                <h3>Wallet top up By card</h3>


                                <!-- <===============PAY-WITH-Mobile===========> -->
                                <div class="">
                                    <label class="amountData" style="display: none;">Amount</label>
                                    <input style="display: none;" min="100" class="form-control amountData" type="number" name="cpm_amount" placeholder="enter amount in numbers" value="<?php echo $amount?>" />
                                    <span id="cpm_amount_err" class="text-danger"></span>
                                    <label for="cpm_currency">Currency</label>
                                    <input id="cpm_currency" readonly="" class="form-control" name="cpm_currency" value="<?php echo $currencyCheck;?>" />
                                    
                                    <span id="cpm_currency_err" class="text-danger"></span>


                                    <!-- by card payment -->
                                    <label class="cardConfig">Market</label>
                                    <select name="cpm_Market" class="form-select form-control cardConfig" aria-label="Default select example" onchange="checkMarket()">
                                        <?php if($cinetpayMarket!='')
                                        {
                                        foreach ($cinetpayMarket as $value) {
                                        ?>
                                        <option value="<?php echo $value['market_name'];?>"><?php echo $value['market_name']; ?></option>
                                    <?php } } else { ?>

                                        <option value="" >No market available</option>
                                    <?php }?>
                                    </select>
                                   
                                    

                                    <label class="cardConfig-op">Operator</label>
                                    <select name="cpm_Operator" class="form-select form-control cardConfig-op" aria-label="Default select example" onchange="checkOperator()">
                                             <?php if($cinetpayOperatorCard!='')
                                        {
                                        foreach ($cinetpayOperatorCard as $value) {
                                        ?>
                                        <option value="<?php echo $value['operator'];?>" ><?php echo $value['operator']; ?></option>
                                    <?php } } else { ?>

                                        <option value="" >No operator available</option>
                                    <?php }?>
                                    </select>
                                    
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
                                    <input type="hidden" name="market_val" id="market_val" value="">
                                    <input type="hidden" name="operator_val" id="operator_val" value="">
                                    <input type="hidden" name="debug" value="0" />
                                    <!--    <label>Wallet</label>
        <select name="cpm_wallet" class="form-select form-control" id="cpm_wallet" aria-label="Default select example">
            <option value="0">Pay</option>
            <option value="1">Topup the wallet</option>
        </select> -->
                                    <!--    <div class="row">
              <div class="col-md-6">
                <label for="state">State</label>
                <input type="text" id="state" name="state" placeholder="NY">
              </div>
              <div class="col-md-6">
                <label for="zip">Zip</label>
                <input type="text" id="zip" name="zip" placeholder="10001">
              </div>
            </div> -->
                                    <!--    <label>
          <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
        </label> -->
                                    <p class="text-center mt-3">
                                        <button style="color: green;" name="submit" class="cinetpay-button large form-control">Proceed for payment</button>
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
function checkMarket(){
   // if($("select[name='cpm_Market']").is(":selected")) {
         //write your code 
         $("#market_val").val($("select[name='cpm_Market']").val()); 
         console.log($("select[name='cpm_Market']").val());
          
    // }
}

function checkOperator(){
   // if($("select[name='cpm_Operator']").is(":selected")) {
         //write your code 
         $("#operator_val").val($("select[name='cpm_Operator']").val()); 
         console.log($("select[name='cpm_Operator']").val());
          
    // }
}

    $(document).ready(function () {
     // market

         $("#market_val").val($("select[name='cpm_Market']").val()); 
    // operator
        $("#operator_val").val($("select[name='cpm_Operator']").val()); 

        // $('#cpm_custom').val("{'payment_by':'<?php //echo $payment_by; ?>','payment_to':'<?php //echo $payment_to; ?>','payment_mode':'<?php //echo $payment_mode; ?>','payment_for':'<?php //echo $payment_for; ?>','reference_id':'<?php //echo $reference_id; ?>','Wallet':'0'}");
        $('#confirm-form-div').hide();
        $("#payment_form").submit(function (e) {
            $('.text-danger').text('');
            e.preventDefault();
            var fd = new FormData(document.getElementById("payment_form"));
            $.ajax({
                type: "POST",
                url: '<?= base_url("index.php/web/CinetPay/addPayment"); ?>',
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
        });
    });

// show and set value for amount field if it is wallet topup request
  var payment_for_wallet = "<?php echo $payment_for;?>";
  if(payment_for_wallet == 3)
  {
    $('.amountData').css("display", "block");
  }

        // radio button functions
    var test = $(".payment_mode").val();
    if (test == "card") {
        $(".phoneConfig-op").prop("disabled", true);
        $(".cardConfig-op").prop("disabled", false);
        $(".phoneConfig").prop("disabled", true);
        $(".cardConfig").prop("disabled", false);
        $(".phoneConfig").hide();
        $(".cardConfig").show();
        $(".phoneConfig-op").hide();
    } else if (test == "phone") {
        $(".cardConfig-op").prop("disabled", true);
        $(".phoneConfig-op").prop("disabled", false);
        $(".phoneConfig").prop("disabled", false);
        $(".cardConfig").prop("disabled", true);
        $(".phoneConfig").show();
        $(".cardConfig").hide();
        $(".cardConfig-op").hide();
    }

    $("input[name='payemtMode']").click(function () {
        var test = $(this).val();
        if (test == "card") {
            $(".phoneConfig-op").prop("disabled", true);
            $(".cardConfig-op").prop("disabled", false);
            $(".cardConfig").prop("disabled", false);
            $(".phoneConfig").prop("disabled", true);
            $(".phoneConfig").hide();
            $(".phoneConfig-op").hide();
            $(".cardConfig").show();
            $(".cardConfig-op").show();
            $(".payment_mode").removeAttr("value");
            $(".payment_mode").val("card");
        } else {
            $(".cardConfig-op").prop("disabled", true);
            $(".phoneConfig-op").prop("disabled", false);
            $(".phoneConfig").prop("disabled", false);
            $(".cardConfig").prop("disabled", true);
            $(".phoneConfig").show();
            $(".cardConfig").hide();
            $(".cardConfig-op").hide();
            $(".phoneConfig-op").show();
            $(".payment_mode").removeAttr("value");
            $(".payment_mode").val("phone");
        }
    });
</script>
