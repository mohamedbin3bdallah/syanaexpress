<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller
{
  /*For localhost*/
 
 // public $sidebar= 'common/sidebarlocal.php';
    /*For sever*/
  public $sidebar='common/sidebar.php';
    public function __construct()
    {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> database();
        $this -> load->library('api');
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');
        $this -> load -> model('Api_model');
    }
	
	/*
	** All Artist Wallets
	*/
    public function index()
    {
      if(isset($_SESSION['name']))
      {
          $data['wallets'] = $this->Api_model->getArtistWallets();
          $data['page'] = 'artist_wallets';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('wallets/index.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Wallet Transactions
	*/
    public function transactions($id)
    {
      if(isset($_SESSION['name']))
      {
		  $settings = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
          $name = ($settings->value == 'arabic')? 'name_ar':'name_en';
          $data['transactions'] = $this->Api_model->getWalletTransactionsByArtist($id,$name);
          $data['page'] = 'artist_wallet_transactions';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('wallets/transactions.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Transfer Cash Requests
	*/
    public function transferCashRequests()
    {
      if(isset($_SESSION['name']))
      {
          $data['transfer_cash_requests'] = $this->Api_model->getArtistTransferCashRequests();
          $data['page'] = 'transfer_cash_requests';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('wallets/transferCashRequests.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Edit Transfer Cash Request
	*/
    public function editTransferCashRequest($id)
    {
      if(isset($_SESSION['name']))
      {
          $data['transfer_cash_request'] = $this->Api_model->getArtistTransferCashRequestById($id);
          $data['page'] = 'edit_transfer_cash_request';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('wallets/edit_transferCashRequest.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Update Transfer Cash Request
	*/
    public function updatetransferCashRequest($id)
    {
      if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('comment', get_phrase('comment') , 'trim|required|max_length[1000]');
		  $this->form_validation->set_rules('status', get_phrase('status') , 'trim|required|integer');
		  
		  if ($this->form_validation->run() == FALSE)
          {
			$data['transfer_cash_request'] = $this->Api_model->getArtistTransferCashRequestById($id);
			$data['page'] = 'edit_transfer_cash_request';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('wallets/edit_transferCashRequest.php', $data);
			$this->load->view('common/footer.php');
          }
          else
          {			
			$data = array('comment'=>set_value('comment'), 'status'=>set_value('status'), 'updated_by'=>$_SESSION['id'], 'updated_at'=>date("Y-m-d H:i:s"));
			
			if(set_value('status') == 2) $updated = $this->Api_model->updateSingleRow('artist_wallet_transfer_cash_requests', array('id'=>$id), $data);
			elseif(set_value('status') == 1)
			{
				$artist_wallet_transfer_cash_request = $this->Api_model->getSingleRow('artist_wallet_transfer_cash_requests',['id'=>$id]);
				$artist_wallet = $this->Api_model->getSingleRow('artist_wallet',['artist_id'=>$artist_wallet_transfer_cash_request->artist_id]);
				if($artist_wallet_transfer_cash_request->amount <= $artist_wallet->amount)
				{
					$this->Api_model->insertGetId('artist_wallet_transactions',['artist_id'=>$artist_wallet_transfer_cash_request->artist_id, 'artist_wallet_transaction_types_id'=>4, 'amount'=>$artist_wallet_transfer_cash_request->amount, 'created_by'=>$_SESSION['id'], 'created_at'=>date("Y-m-d H:i:s")]);
					$this->Api_model->updateSingleRow('artist_wallet', ['artist_id'=>$artist_wallet_transfer_cash_request->artist_id], ['amount'=>$artist_wallet->amount - $artist_wallet_transfer_cash_request->amount]);
					$updated = $this->Api_model->updateSingleRow('artist_wallet_transfer_cash_requests', array('id'=>$id), $data);
				}
				else
				{
					$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'big_amount';
					redirect('Wallet/editTransferCashRequest/'.$id);
				}
			}
			if($updated)
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Wallet/transferCashRequests');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Wallet/editTransferCashRequest/'.$id);
			}
          }
      }
      else
      {
          redirect('');
      }
    }
}