<?php

namespace Jinom\Payment;

use Jinom\Payment\Traits\Charge;
use Jinom\Payment\Traits\CreateCustomerDetail;
use Jinom\Payment\Traits\CreateItemDetail;
use Jinom\Payment\Traits\CreateOrderId;
use Jinom\Payment\Traits\CreateTransactionDetail;
use Jinom\Payment\Traits\Snap;

class Transaction {

    use Snap, CreateItemDetail, CreateTransactionDetail, CreateCustomerDetail, CreateOrderId;

    public $customer_details = [];
    public $transaction_details = [];
    public $item_details = [];
    public $created_by = null;
    public $transaction = null;
    public $expired_at = null;
    public $created_at = null;

    const CSTORE = 'cstore';
    const ECHANNEL = 'echannel';
    const BANK_TRANSFER = 'bank_transfer';
    const CREDIT_CARD = 'credit_card';
    const GOPAY = 'gopay';
    const PERMATA = 'permata';

    const BCA = 'bca';
    const BNI = 'bni';
    const BRI = 'bri';
    const MANDIRI = 'mandiri';
    const ALFAMART = 'alfamart';


    public $jinom_payment;
    

    public function __construct()
    {
    }

    public function setExpiredDate($expired_at)
    {
        $this->expired_at = $expired_at;
    }

    public function setCreatedAt($date) {
        $this->created_at = $date;
    }

    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }

    public function getExpiredDate()
    {
        if ($this->expired_at) return $this->expired_at;

        $expired_at = date('Y-m-d H:i:s', "+1 day");

        return $expired_at;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function setTransaction($transaction) {
        $this->transaction = $transaction;
    }

    public function getPaymentMethod() {
        $payment_type = $this->transaction_details["payment_type"];

        if($payment_type == Transaction::BANK_TRANSFER) {
            return $this->transaction_details['va_numbers'][0]['bank'];
        } else if($payment_type == Transaction::CSTORE) {
            return $this->transaction_details['store'];
        } else if($payment_type == Transaction::ECHANNEL) 
        {
            return Transaction::MANDIRI;
        } 
        
        return "";
    }

    public function getPaymentListAlias() {
        $payment_type = $this->transaction_details["payment_type"];

        if($payment_type == Transaction::BANK_TRANSFER) {
            return $this->transaction_details['va_numbers'][0]['bank'];
        } else if($payment_type == Transaction::CSTORE) {
            return $this->transaction_details['cstore']['store'];
        } else if($payment_type == Transaction::ECHANNEL) 
        {
            return Transaction::MANDIRI;
        }
    }

    public function getRequestPaymentListAlias() {
        if(isset($this->transaction_details["payment_type"])) {
            $payment_type = $this->transaction_details["payment_type"];
            if($payment_type == Transaction::BANK_TRANSFER) {
                return $this->transaction_details[Transaction::BANK_TRANSFER]['bank'];
            } else if($payment_type == Transaction::CSTORE) {
                return $this->transaction_details['cstore']['store'];
            } else if($payment_type == Transaction::ECHANNEL) 
            {
                return Transaction::MANDIRI;
            }
        } else {
            return '';
        }
    }

    public function getPaymentCode() {
        $payment_type = $this->transaction_details["payment_type"];

        if($payment_type == Transaction::BANK_TRANSFER) {
            return $this->transaction_details['va_numbers'][0]['va_number'];
        } else if($payment_type == Transaction::CSTORE) {
            return $this->transaction_details['payment_code'];
        } else if($payment_type == Transaction::ECHANNEL) 
        {
            return $this->transaction_details['biller_code'] . $this->transaction_details['bill_key'];
        }
        
        return "";
    }
     
}