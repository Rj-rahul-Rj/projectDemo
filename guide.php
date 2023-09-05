<?php
// date_default_timezone_set('asia/kolkata');
// echo  date('d-m-Y H:i:s',1570265472);echo '<br>';
// echo  date('d-m-Y H:i:s',1572857472);echo '<br>';
// echo  date('d-m-Y H:i:s', strtotime(date('d-m-Y H:i:s').'+0 days'));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Api Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2 class="text-center mt-5">Ecity APP Guide</h2>
  
  <div id="accordion">

    <div class="card">
      <div class="card-header">
        <a class="collapsed card-link" data-toggle="collapse" href="#section_3">
        Base Url
      </a>
      </div>
      <div id="section_3" class="collapse show" data-parent="#accordion">
        <div class="card-body">
           
           <p>https://alphaxtech.net/ecity/index.php/</p>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-header">
        <a class="card-link" data-toggle="collapse" href="#collapseOne">
          API Status Code
        </a>
      </div>
      <div id="collapseOne" class="collapse " data-parent="#accordion">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p>200 => success</p>
              <p>205 => Your account is not verified by admin</p>
              <p>204 => If no active plan found .</p>
         
        
            </div>
            <div class="col-md-6">
          <p>400 => Something went wrong. Try again/Email not verified</p>
          <p>401 => Unauthorized access</p>
          <p>402 => Header is missing</p>
          <p>404 => Not found</p>
          <p>405 => Method not allowed</p>
          <p>408 => Session expire</p>
          <p>410 => Otp expire</p>
            </div>
          </div>
          
        
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <a class="collapsed card-link" data-toggle="collapse" href="#section_1">
        Constant Detail 
      </a>
      </div>
      <div id="section_1" class="collapse" data-parent="#accordion">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
               <h5>Carts Status</h5>
               <p>1 = active cart <br>
                2 = ordered <br>
                3 = deleted <br>
              </p>
            </div>
             <div class="col-md-4">
               <h5>Category Type</h5>
               <p>1 = business type <br>
                  2 = event category <br>
                  3 = company category
                </p>
            </div>
			 <div class="col-md-4">
               <h5>Category status</h5>
               <p>1 = active <br>
                  2 = deactive <br>
                  3 = delete
                </p>
            </div>
			<div class="col-md-4">
               <h5>Contact status</h5>
               <p>1 = my contact <br>
                  2 = block by me <br>
                  3 = block by admin
                </p>
            </div>
			<div class="col-md-4">
               <h5>Contact favourite_status</h5>
               <p>1 = favorite <br>
                  2 = not favorite <br>
                </p>
            </div>

            <div class="col-md-4">
               <h5>Contact_groups Status</h5>
               <p>
                  1 = active <br>
                  2 = deleted by owner <br>
                  3 = deleted by admin <br>
                 
                </p>
            </div>
			 <div class="col-md-4">
               <h5>contact_group_members Status</h5>
               <p>
                  1 = active member <br>
                  2 = remove by group admin <br>
                  3 = remove by self <br>
                  4 = remove by super admin<br>
                 
                </p>
            </div>

			<div class="col-md-4">
               <h5>events Status</h5>
               <p>
                  1 = active<br>
                  2 = done<br>
                  3 = pospond <br>
                  4 = block by admin<br>
                 
                </p>
            </div>
			<div class="col-md-4">
               <h5>Events access_option</h5>
               <p>
                  1 = public <br>
                  2 = private <br>
                 
                </p>
            </div>
			<div class="col-md-4">
               <h5>favourite_unfavourite type</h5>
               <p>
                  1 = product<br>
                  2 = store <br>
                  3 = upcommings <br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>favourite_unfavourite Status</h5>
               <p>
                  1 = fav <br>
                  0 = unfav<br>
                 
                </p>
            </div>
			<div class="col-md-4">
               <h5>feedback status</h5>
               <p>
                  1 = new<br>
                  2 = read <br>
                  3 = clicked <br>
                  4 = deleted <br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>group_conversation status</h5>
               <p>
                  1 = active<br>
                  2 = delete by user <br>
                  3 = delete for everyone <br>
                  4 = block by admin <br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>invitee status</h5>
               <p>
                  1 = request pending by invitee<br>
                  2 = accepted by invitee<br>
                  3 = rejected by invitee<br>
                  4 = request pending for organizer<br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>favourite_unfavourite type</h5>
               <p>
                  1 = product<br>
                  2 = store <br>
                  3 = upcommings <br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>merchant_invoice status</h5>
               <p>
                  1 = only generated<br>
                  2 = pending for payment<br>
                  3 = decline by merchant <br>
                  4 = payed by merchant <br>
                  5 = decline by admin <br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>merchant_subscription status</h5>
               <p>
                  1 = invoice pending<br>
                  2 = active <br>
                  3 = inactive by admin <br>
                  4 = expired<br>
                
                </p>
            </div>
			<div class="col-md-4">
               <h5>notifications status</h5>
               <p>
                  1 = new<br>
                  2 = read <br>
                  3 = clicked <br>
                  3 = deleted <br>
                
                </p>
            </div>
			
             <div class="col-md-4">
              <h5>Order Status</h5>
             <p> 1 = new order <br> 
                 2 = dispatch ready<br> 
                 3 = order on the way<br> 
                 4 = order deliverd<br> 
                 5 = cancel by user<br> 
                 6 = cancel by admin<br> 
                
                </p>
            </div>
             <div class="col-md-4">
              <h5>Order Payment Status</h5>
               <p>
                  1 = payment due<br>
                  2 = payment due bcz after delivery<br>
                  3 = payment done<br>
                  4 = payment failed<br>
               
              </p>
            </div>
             <div class="col-md-4"> 
              <h5>Product Status</h5>
               <p>
                 1 = active<br>
                 2 = inactive by owner<br>
                 3 = inactive by admin<br>
                 4 = currently for deliverable<br>
                 5 = deleted
                  
              </p>
            </div>
			  <div class="col-md-4"> 
              <h5>recently_viewed Status</h5>
               <p>
                  1 = active<br>
                  0 = inactive<br>
                
              </p>
            </div>
			  <div class="col-md-4"> 
              <h5>stores Status</h5>
               <p>
                  1 = pending for approve by admin<br>
                  2 = active<br>
                  4 = inactive by admin<br>
                  3 =inactive for now
              </p>
            </div>
			  <div class="col-md-4"> 
              <h5>subscription beacon_type</h5>
               <p>
                  1 = virtual<br>
                  2 = physical<br>
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>subscription threshould</h5>
               <p>
                  1 = yes<br>
                  0 = no<br>
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>subscription status</h5>
               <p>
                  1 = active<br>
                  2 = inactive<br>
                  3 = delete<br>
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>users os_type</h5>
               <p>
                  1 = android<br>
                  2 = ios<br>
                  3 = windows<br>
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>users language</h5>
               <p>
                  1 = English<br>
                  2 = French<br>
                 
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>users user_type</h5>
               <p>
                  1 = user<br>
                  2 = merchant<br>
                  2 = super admin<br>
                  4 = admin user<br>
                  5 = developer have all access<br>
                 
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>users finger_print_status</h5>
               <p>
                  1 = enable<br>
                  0 = disable<br>
                 
              </p>
            </div>
             <div class="col-md-4"> 
              <h5>Users Status</h5>
               <p>
                  1 = email and admin verification pending <br>
                  2 = pending email verification<br>
                  3 = pending admin verification<br>
                  4 = blocked<br>
                  5 = account deleted,<br>
                  6 = account verified<br>
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>user_beacons Status</h5>
               <p>
                  1 = only alot by admin <br>
                  2 = need to approve by admin<br>
                  3 = working/active<br>
                  4 = inactive by admin<br>
                  5 = inactive by merchant<br>
                  6 = inactive by payment<br>
                  7 = deleted<br>
              </p>
            </div>
			 <div class="col-md-4"> 
              <h5>user_setting all_notification</h5>
               <p>
                  1 = enable <br>
                  0 = disable<br>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>


<div class="card">
      <div class="card-header">
        <a class="collapsed card-link" data-toggle="collapse" href="#section_2">
        Header Detail 
      </a>
      </div>
      <div id="section_2" class="collapse" data-parent="#accordion">
        <div class="card-body">
           <h5>#app_key && app_secret</h5>
           <p>It shohuld be in  <b>SignupstepOne, SignupstepTwo, Signin, acountsetup, signup,forgotpassword,refresh token </b> API. </p>
           
           <h5>#authtoken</h5>
           <p> It's mandatory in all remaining request Except all of the above.Get it from above API responce. </p>
        </div>
      </div>
    </div>
    
  </div>
</div>
    
</body>
</html>
