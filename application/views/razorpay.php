<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--  <title>Bootstrap Example</title>-->
<!--  <meta charset="utf-8">-->
<!--  <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<!--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!--</head>-->
<!--	<body>-->
<!--		<div class="container" style="padding-top: 50px;">-->
<!--			<div style="text-align: center;">-->
<!--<form action="<?= base_url('WebService/razorPayment')?>" method="POST">-->
<!--<input type="hidden" name="amount" value="<?= $amount;?>">-->
<!--<input type="hidden" name="userId" value="<?= $userId;?>">-->
<!--<input type="hidden" name="invoiceId" value="<?= $invoiceId;?>">-->
<!--<input type="text" name="amount" value="<?= $amount;?>">-->
<!--<script-->
<!--    src="https://checkout.razorpay.com/v1/checkout.js"-->
<!--    data-key="rzp_live_rPgZ7YS0Ruka2r"-->
<!--    data-amount="<?= $amount.'00';?>"-->
<!--    data-buttontext="Pay with Razorpay"-->
<!--    data-name="<?= $userName;?>"-->
<!--    data-description="Purchase Description"-->
<!--    data-image="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRg8QEeGSbjvkfRassM8VmwMYQrrSFpsaYOoGr2rTXhCUdw0SdG"-->
<!--    data-prefill.name="<?= $userName;?>"-->
<!--    data-prefill.email="<?= $email;?>"-->
<!--    data-theme.color="#F37254">-->
<!--   </script>-->

<!--</form>-->
<!--</div>-->
<!--		</div>-->
<!--	</body>-->
<!--</html>-->

<div class="container" style="padding-top: 50px;">
<div style="text-align: center;">
<button id="rzp-button1">Pay</button>
</div></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "rzp_test_bhyVZKEXHJ6wV4",
    "amount": "<?= $amount.'00';?>", // 2000 paise = INR 20
    "name": "<?= $userName;?>",
    "description": "Purchase Description",
    "image": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRg8QEeGSbjvkfRassM8VmwMYQrrSFpsaYOoGr2rTXhCUdw0SdG",
    "handler": function (response){
        alert(JSON.stringify(response));
      if(response.razorpay_payment_id){
       $.ajax({
          type: "POST",
          url: "<?= base_url()?>Webservice/razorPayment",
          data: {'razorpay_payment_id':response.razorpay_payment_id,'amount' : <?= $amount.'00';?>,'userId' : <?= $userId;?>,'invoiceId' : <?= $invoiceId;?>},
          success: function (data) {
            // if(data == 1)
            // {
             location.assign('<?= base_url('Webservice/payusuccess')?>');
          //  }
             
        }
          });
    }
 },
    "prefill": {
        "name": "<?= $userName;?>",
        "email": "<?= $email;?>"
    },
    "notes": {
        "address": "<?= $address;?>"
    },
    "theme": {
        "color": "#F37254"
    }
};
var rzp1 = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
