<?php $this->dispatch("layout/header/3/1/a");?>
<h2 class="page-heading"><?php echo $this->get_label('paypal success');?></h2>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-2 payment-details">

  <div class="row">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
      <?php echo $this->get_label('payment status');?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
      <?php echo $this->get_variable('payment_status');?>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
      <?php echo $this->get_label('payment amount');?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
      <?php
        $paymentCurrency = $this->get_variable('payment_currency');
        $systemCurrency  = $this->get_variable('system_currency');
        $paymentAmount          = $this->get_variable('payment_amount');
        $systemCurrencyAmount   = $this->get_variable('payment_amount_system_currency');

        if($paymentCurrency == $systemCurrency)
        echo $this->get_number_format($paymentAmount)." ".$paymentCurrency;
        else
        echo $this->get_number_format($paymentAmount)." ".$paymentCurrency." / ".$this->get_number_format($systemCurrencyAmount)." ".$systemCurrency;
      ?>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
      <?php echo $this->get_label('transaction id');?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
      <?php echo $this->get_variable('txn_id');?>
    </div>
  </div>


  <?php if($this->get_variable('payer_email') != ""){?>
    <div class="row">
      <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
        <?php echo $this->get_label('payer email');?>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
      <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
        <?php echo $this->get_variable('payer_email');?>
      </div>
    </div>
  <?php }?>

  <?php if($this->get_variable('receiver_email') != ""){?>
    <div class="row">
      <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
        <?php echo $this->get_label('receiver email');?>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
      <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
        <?php echo $this->get_variable('receiver_email');?>
      </div>
    </div>
  <?php }?>

  <?php if($this->get_variable('payment_status') != "Completed"){?>
    <div class="row">
      <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 mb-2">
        <?php echo $this->get_label('pending reason');?>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 mb-2">:</div>
      <div class="col-lg-9 col-md-8 col-sm-7 col-xs-5 mb-2">
        <?php echo $this->get_variable('pending_reason');?>
      </div>
    </div>
  <?php }?>

</div>
<?php $this->dispatch("layout/footer");?>
