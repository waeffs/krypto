<?php

class Coinspot extends Exchange {

  private $Api = null;

  public function __construct($User, $App, $Credentials = null){
    parent::__construct($User, $App, $this, $Credentials);
    parent::_setExchangeName('coinspot');
  }

  public function _getApi(){

    if(!is_null($this->Api)) return $this->Api;

    if(!is_null($this->Credentials)){
      $this->Api = new \ccxt\coinspot([
        'apiKey' => App::encrypt_decrypt('decrypt', $this->Credentials["key_coinspot"]),
        'secret' => App::encrypt_decrypt('decrypt', $this->Credentials["secret_coinspot"])
      ]);
    } else {
      $this->Api = new \ccxt\coinspot([
        'apiKey' => App::encrypt_decrypt('decrypt', $this->_isActivated()['key_coinspot']),
        'secret' => App::encrypt_decrypt('decrypt', $this->_isActivated()['secret_coinspot'])
      ]);
    }

    return $this->Api;

  }

  public function _getName(){ return 'Coinspot'; }
  public function _getTable(){ return 'coinspot_krypto'; }
  public function _getLogo(){ return 'coinspot.png'; }
  public function _isActivated(){ return parent::_isActivated(); }

  public static function _formatPair($from, $to){
    return $from.'/'.$to;
  }

  public function _createOrder($symbol, $type, $side, $price = null, $params = [], $Balance = null, $InternalOrderID = null, $Type = "market", $order_price = null){
    if(!$Balance->_isPractice()){
      if($this->_isActivated() == false) throw new Exception($this->_getExchange()->_getName().' is not enable on your account', 1);

      $priceUnit = parent::_getPriceTrade($symbol);

      $order = $this->_getExchange()->_getApi()->create_order($symbol, $type, $side, $priceUnit, $price, $params);
    }

    parent::_saveOrder($symbol, $type, $side, $price, $params, $Balance, $order);
  }

  public function _getFormatedBalance(){
    $balance = $this->_getApi()->fetch_balance();
    $res = [];
    foreach ($balance as $key => $value) {
      if($key == 'info' || $key == 'used' || $key == 'free' || $key == 'total') continue;
      $res[$key] = [
        'free' => $value['free'],
        'used' => $value['used']
      ];
    }
    return $res;
  }

  public function _getBalance($fetchall = false){
    $balanceList = $this->_getFormatedBalance();
    uasort($balanceList, array( $this, '_balanceSort' ));
    return $balanceList;
  }

  public function _getOrderBook($symbol = null){
    $v = $this->_getApi()->fetch_open_orders();

  }


}

?>
