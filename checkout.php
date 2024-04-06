<?php
//INCLUDE THE ACCESS TOKEN AS IT WILL BE USED
include 'accessToken.php';

  if (isset($_POST['phone'])) {
  $phone = $_POST['phone'];
  //IMPORTANT TO ENSURE THAT PHONE DATA SENT HAS 254 PREFIX AS A USER CAN WRITE 07...
  $first3digits = substr($phone, 0, 3);
  if ($first3digits != '254') {
    // If the phone number doesn't start with '254', add the prefix
    $phone = '254' . (int)$phone;
  }

   
date_default_timezone_set('Africa/Nairobi');
$processrequesturl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; //USE SANDBOX URL IF TESTING
$callbackurl = 'https://01-01-01.ngrok-free.app/folder_name/callback.php'; //USE A LIVE WEBSITE URL OR NGROK IF YOU DONT HAVE A LIVE WEBSITE. THIS IS WHERE THE API WILL MAKE CALLBACKS
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919"; //FOUND IN THE MPESA EXPRESS SIMULATE SECTION IN DARAJA WEBSITE
$BusinessShortCode = '174379'; //FOUND IN MPESA EXPRESS SIMULATE SECTION IF CODING FOR LIVE WEBSITE USE REAL PAYBILL NUMBER
$Timestamp = date('YmdHis');
$TransactionType = 'CustomerPayBillOnline';
$Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);// ENCRYPT  DATA TO GET PASSWORD
$PartyA = $phone;
$PartyB = '174379'; //FOUND IN MPESA EXPRESS SIMULATE SECTION
$AccountReference = 'Enter account reference'; //COULD BE NAME OF BUSINESSS
$TransactionDesc = 'CustomerPaybillOnline';
$Amount = '1'; //1 SHILLING FOR TESTING PURPOSES 

$requestBody = array(
  "BusinessShortCode" => $BusinessShortCode,
  "Password" => $Password, 
  "Timestamp" => $Timestamp,
  "TransactionType" => $TransactionType,
  "Amount" => $Amount,
  "PartyA" => $PartyA,
  "PartyB" => $PartyB,
  "PhoneNumber" => $PartyA,
  "CallBackURL" => $callbackurl,
  "AccountReference" => $AccountReference,
  "TransactionDesc" => $TransactionDesc
);

$ch = curl_init($processrequesturl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

//YOU CAN ECHO  RESPONSE TO SEE OUTPUT
$data = json_decode($response); //DECODING JSON OBJECT TO RETRIEVE CHECKOUTREQUESTID AND RESPONSECODE
$CheckoutRequestID = $data->CheckoutRequestID;
$ResponseCode = $data->ResponseCode;
if ($ResponseCode == "0") {
  echo "<script>window.location.href='checkout.php?success=Please Enter Your Mpesa Pin To Complete The Transaction'</script>";
}else{
  echo "<script>window.location.href='checkout.php?error=Please Try Again'</script>";
}
  }
   else{
    echo "<script>window.location.href='checkout.php?error=Please Populate the cart</script>";
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MPESA Prompt</title>
     <style>
        .checkoutcontainer{
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .checkout{
          width: 80%;
          min-height: 100%;
          margin: 30px 0;
          background-color: #f0f0f8;
          border-radius: 10px;
          box-shadow: 20px 10px 10px 10px #888888;
          padding: 50px;
        }
        .checkout div h1{
          text-align: center;
          color: maroon;
        }
        .secondcontainer{
          margin-top: 20px;
        }

        input[type="number"]{
          width: 90%;
          height: 10%;
          padding: 10px;
        }
        input[class="button"]{
          background-color: maroon;
          border-radius: 10px;
          width: 137px;
          height: 16%;
          color: white;
          font: 18px;
          border: none;
          text-align: center;
          cursor: pointer; 
          padding: 10px;
        }
        input[class="button"]:hover{
          transform: scale(1.05);
        }

 
    </style>
</head>
<body style="background-color: #fcfcfd" >
          
          <!--CHECKOUT CONTAINER-->
          <div class="checkoutcontainer">
            <div class="checkout">
                <div><h1>Checkout</h1></div>
                <div class="firstcontainer">
                    <h4>Pay KES 1.00/= </h4>
                    <ol>
                        <li>Provide your MPESA mobile number below</li>
                        <li>Click 'Pay' and a prompt will appear on your phone requesting you to confirm transaction by providing your MPESA PIN</li>
                        <li>Once completed, you will receive the confirmation SMS for this transaction</li>
                    </ol>
                </div>
                <div class="secondcontainer">
                    <form action="checkout.php" method="POST">
                      <?php
                        if(isset($_GET['success'])){
                          echo "<p style='color:green'>".$_GET['success']."</p>";
                        }elseif(isset($_GET['error'])){
                          echo "<p style='color:red'>".$_GET['error']."</p>";
                        }
                        ?>
                      <b>Provide your phone number below</b><br>
                      <input type="hidden" id="totalInput" name="amount" value="<?php echo $totalAmount * 149; ?>">
                      <input type="number" name="phone" placeholder="Enter your phone number"><br><br>
                      <input type="submit" name="deposit" class="button" value="Pay" >
                    </form>
                </div>
            </div>
          </div>

 </body>
</html>

