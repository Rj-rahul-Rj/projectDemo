<!DOCTYPE html>
<html>
<head>
	<title>Set Password</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<link href="<?php echo base_url(); ?>/frontend_asset/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>/frontend_asset/toastr/toastr.min.js"></script>
</head>
<style type="text/css">
	
	body {
    	background:#333;
	}
    .form_bg {
        background-color:#eee;
        color:#666;
        padding:20px;
        border-radius:10px;
        position: absolute;
        border:1px solid #fff;
        margin: auto;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 520px;
        height: 280px;
    }
    .align-center {
        text-align:center;
    }
    .error{
        color:red;
    }
    .n-height-300{min-height: 300px;max-height: 315px;}
</style>
<body>

  <div class="container">
  	<?php if($token){?>
        <div class="row">
            <div class="form_bg n-height-300">
                <form method="post" action="<?php echo base_url(); ?>index.php/web/App/Set" id="update_password">
                	<p class="text-center <?php echo ($status && $status=='200')?'text-success':'text-danger' ?>"><?php echo $message; ?></p>
                     <h2 class="text-center">Set your password here</h2>
                    <br/>
                    <div class="form-group">
                        <input type="Password"  class="form-control" id="new_password" name="password" value="" placeholder="Enter Password">
                 
                    </div>
                    <input type="hidden" class="form-control" name="token" value="<?php echo $token; ?>">
                    
                    <div class="form-group">
                        <input type="Password"  class="form-control" id="confirm_password" name="confirmPassword" value="" placeholder="Confirm password">
                    </div>
                  <!--   <br/> -->
                    <div class="align-center">
                        <button type="submit" class="btn btn-default update_pass" >SET PASSWORD</button>
                    </div>
                </form>
            </div>
        </div>
    <?php }
    else{
    	?>
         <div class="row">
            <div class="form_bg n-height-300">

                	<p class="text-center <?php echo ($status && $status=='200')?'text-success':'text-danger' ?>" style="padding: 90px 21px;font-size: 23px;"><?php echo $message; ?></p>
            </div>
         </div>
    	<?php
    }
    ?>
    </div>
</body>
</html>