<?php
  if(isset($_GET['c'])) {
    //echo base_url('Stripe/Payment/success');
    //echo base_url('Stripe/Payment/fail'); 
  }
  else {
    header('Location: '.$url.'');
  }
?>