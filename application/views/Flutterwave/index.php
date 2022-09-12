 <script src="http://bartanwalaapp.com/admin/assets/node_modules/jquery/dist/jquery.min.js"></script>
<script>
  document.onkeydown = function(e) {
    if(e.keyCode == 123) {
     return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
     return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
     return false;
    }
    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
     return false;
    }

    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
     return false;
    }      
 }

$(document).ready(function () {
    //Disable full page
    $("body").on("contextmenu",function(e){
        return false;
    });

    $('body').bind('cut copy', function (e) {
        e.preventDefault();
    });
    
    $('body').bind('cut copy', function (e) {
        e.preventDefault();
    });
});
</script>
<body onLoad="disableclick()">
<form>
    <script src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()">Pay Now</button>
</form>
<script>
    const API_publicKey = "FLWPUBK-72e83eeb34bdaee3fdd4db67e25a11cd-X";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "user@example.com",
            amount: 2000,
            customer_phone: "234099940409",
            currency: "EUR",
            country: "NG",
            txref: "rave-123456",
            meta: [{
                metaname: "flightID",
                metavalue: "AP1234"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (
                    response.tx.chargeResponseCode == "00" ||
                    response.tx.chargeResponseCode == "0"
                ) 
                {
                    window.location.href="<?php echo base_url('Stripe/Payment/success'); ?>";
                } else 
                {
                    window.location.href="<?php echo base_url('Stripe/Payment/fail'); ?>";
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }
</script>
</body>