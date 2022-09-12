<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'classes/MyFatoora/PaymentMyfatoorahApiV2.php';



class Payment extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> database();
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');
        $this -> load -> model('Api_model');

    }

    public function index()
    {
        $this -> load ->view('login.php');
    }

    public function makePayment()
    {

        $apiKey = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        $isTestMode=true;

        $syanaInvoiceId= $_GET['invoice_id'];

        $condition['i.invoice_id'] = $syanaInvoiceId;
        $condition['i.flag'] = 0;

        $invoice = $this->Api_model->getInvoiceDetails($condition);

//        print_r($invoice);
//        die;
        if(!$invoice){
            echo "error (Invalid Invoice Number)";
            exit;
        }

        if($invoice->final_amount >0){
            $mfPayment = new PaymentMyfatoorahApiV2($apiKey, $isTestMode);

            $postFields = [
                'NotificationOption' => 'Lnk',
                'InvoiceValue'       => $invoice->final_amount,
                'CustomerName'       => $invoice->name,
                'CustomerMobile'       => $invoice->mobile,
                //'CustomerEmail'       => $invoice->email_id,
                'CallBackUrl'       => 'http://142.93.166.71/payment_feedback',
                'ErrorUrl'       => 'http://142.93.166.71/payment_failure',
                'Language'       => 'ar',
                'CustomerReference'       => $syanaInvoiceId,
                'DisplayCurrencyIso'       => $invoice->currency_code,
                'MobileCountryCode'       => '+966',
            ];
    
            $data = $mfPayment->getInvoiceURL($postFields, 'myfatoorah', $syanaInvoiceId);
            $invoiceId   = $data['invoiceId'];
            $paymentLink = $data['invoiceURL'];
    
    
            $updatedData=['payment_key_id' =>$invoiceId, 'payment_key_type' =>'invoiceId'];
    
            $udpate = $this->Api_model->updateSingleRow('booking_invoice', ['invoice_id' => $syanaInvoiceId], $updatedData);
    
            if(!$udpate){
                echo "error (Un Expected Error Occured)";
                exit;
            }
    
            $data['invoice']=$invoice;
            $data['paymentLink']=$paymentLink;
            $data['invoiceId']=$invoiceId;
            $data['getBookingDetails']=$this->Api_model->getBookingDetails($invoice->booking_id);
            $data['getBookingItems']=$this->Api_model->getBookingItems($invoice->booking_id);
        }else{
            $data['invoice']=$invoice;
            $data['paymentLink']='';
            $data['invoiceId']='';
            $data['getBookingDetails']=$this->Api_model->getBookingDetails($invoice->booking_id);
            $data['getBookingItems']=$this->Api_model->getBookingItems($invoice->booking_id);
        }
        

//        echo "Click on <a href='$paymentLink' target='_blank'>$paymentLink</a> to pay with invoiceID $invoiceId.";
        $this -> load -> view('payment.php', $data);
    }


    public function receivePayment(){
        $paymentId= $_GET['paymentId'];

        $apiKey = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        $isTestMode=true;

        $mfPayment = new PaymentMyfatoorahApiV2($apiKey, $isTestMode);

        $data = $mfPayment->getPaymentStatus($paymentId, 'paymentId');

        if($data->InvoiceStatus != 'Paid'){
            echo "invoice not paid";
            exit;
        }

        $record = $this->Api_model->getSingleRow('booking_invoice', ['payment_key_id' => $data->InvoiceId, 'invoice_id'=> $data->CustomerReference]);

        if(!$record){
            echo "record not found";
            exit;
        }

        $updatedData=['flag' =>1, 'gateway_payment_id' =>$paymentId];

        $udpate = $this->Api_model->updateSingleRow('booking_invoice',  ['payment_key_id' => $data->InvoiceId, 'invoice_id'=> $data->CustomerReference], $updatedData);

        if($udpate){




            echo "Payment successfully updated";
        }
    }

    public function receivePaymentFail(){
        $paymentId= $_GET['paymentId'];

        $apiKey = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        $isTestMode=true;

        $mfPayment = new PaymentMyfatoorahApiV2($apiKey, $isTestMode);

        $data = $mfPayment->getPaymentStatus($paymentId, 'paymentId');

        //print_r($data);

        echo "Payment Failed";
        exit;



    }


    public function coupon(){
        $copoun= $_POST['copoun'];
        $invoice_id= $_POST['invoice_id'];

        $copounRecord = $this->Api_model->getSingleRow('discount_coupon', ['coupon_code' => $copoun, 'status'=> 1]);
        $invoiceRecord = $this->Api_model->getSingleRow('booking_invoice', ['invoice_id' => $invoice_id]);

        if(!$copounRecord || !$invoiceRecord){
            redirect(base_url('pay?invoice_id='.$invoice_id.'&couponError=1'));
        }

        if($copounRecord->discount_type == 1){ // percentage
            $discountAmount = ($invoiceRecord->total_amount * $copounRecord->discount)/100;

        }else{ //fixed amount
            $discountAmount =  $copounRecord->discount;
        }

        $updatedData['coupon_code'] = $copoun;
        $updatedData['discount_amount'] = $discountAmount;
        $updatedData['final_amount'] = $invoiceRecord->total_amount - $discountAmount;

        $udpate = $this->Api_model->updateSingleRow('booking_invoice',  ['invoice_id' => $invoice_id], $updatedData);

        if(!$updatedData){
            redirect(base_url('pay?invoice_id='.$invoice_id.'&couponError=1'));
        }

        redirect(base_url('pay?invoice_id='.$invoice_id.'&couponSuccess=1'));

    }
}