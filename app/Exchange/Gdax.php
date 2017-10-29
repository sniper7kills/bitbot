<?php

namespace App\Exchange;

class Gdax
{
    protected $url = 'https://api.gdax.com';

    protected $key;
    protected $secret;
    protected $passphrase;
    protected $defaultPair;

    protected $endpoints = array(
        'accounts'   => array('method' => 'GET', 'uri' => '/accounts'),
        'account'    => array('method' => 'GET', 'uri' => '/accounts/%s'),
        'ledger'     => array('method' => 'GET', 'uri' => '/accounts/%s/ledger'),
        'holds'      => array('method' => 'GET', 'uri' => '/accounts/%s/holds'),
        'place'      => array('method' => 'POST', 'uri' => '/orders'),
        'cancel'     => array('method' => 'DELETE', 'uri' => '/order/%s'),
        'cancel_all' => array('method' => 'DELETE', 'uri' => '/orders/'),
        'orders'     => array('method' => 'GET', 'uri' => '/orders'),
        'order'      => array('method' => 'GET', 'uri' => '/orders/%s'),
        'fills'      => array('method' => 'GET', 'uri' => '/fills'),
        'products'   => array('method' => 'GET', 'uri' => '/products'),
        'book'       => array('method' => 'GET', 'uri' => '/products/%s/book'), // ?level=2
        'ticker'     => array('method' => 'GET', 'uri' => '/products/%s/ticker'),
        'trades'     => array('method' => 'GET', 'uri' => '/products/%s/trades'),
        'stats'      => array('method' => 'GET', 'uri' => '/products/%s/stats'),
        'rates'      => array('method' => 'GET', 'uri' => '/products/%s/candles'),
        'currencies' => array('method' => 'GET', 'uri' =>  '/currencies'),
        'time'       => array('method' => 'GET', 'uri' => '/time'),
        'position'   => array('method' => 'GET', 'uri' => '/position'),
        'reports'    => array('method' => 'GET', 'uri' => '/reports'),
        'coinbase-accounts' => array('method' => 'GET', 'uri' => '/coinbase-accounts'),
    );

    function __construct()
    {
        $this->key = env('GDAX.KEY');
        $this->secret = env('GDAX.SECRET');
        $this->passphrase = env('GDAX.PASSPHRASE');
        $this->defaultPair = env('GDAX.DEFAULTPAIR');
    }

    public function test()
    {
        echo "\nTESTING START...\n\n";
        //print_r($this->call('candles',null,array('start'=>'2017-09-27T02:23:00Z','end'=>'2017-09-59T02:24:00Z','granularity'=>1)));
        //echo print_r($this->call('coinbase-accounts')['response']);
        $this->backfill("-2 hours");
        echo "\n\nTESTING END...\n\n";
    }

    public function backfill($startDate=null,$pair=null)
    {
        /*TODO
          Check Database and see if $startdate < most recent candle
        */

        if($pair==null){
            $pair=$this->defaultPair;
        }
        
        if($startDate == null)
        {
            $startDate = strtotime("-2 days");
            $date = Date('Y-m-d',$startDate);
            $time = Date('H:i',$startDate);
            $startDate = $date."T".$time.":00Z";
        }else{
            $startDate = strtotime($startDate);
            $date = Date('Y-m-d',$startDate);
            $time = Date('H:i',$startDate);
            $startDate = $date."T".$time.":00Z";
        }

        $endDate = strtotime($startDate." +6000 seconds");
        if($endDate > strtotime('now')){
            $endDate = strtotime('now');
        }
        $date = Date('Y-m-d',$endDate);
        $time = Date('H:i',$endDate);
        $seconds = Date('s',$endDate);
        $endDate = $date."T".$time.":".$seconds."Z";
        echo $startDate." -> ".$endDate."\n";

        $return = $this->call('rates',null,array('start'=>$startDate,'end'=>$endDate,'granularity'=>'30'));
        $responseCode = $return['responseCode'];
        $response = $return['response'];

        if($responseCode==200){
            $response = json_decode($response);
            foreach($response as $candle){
                $timestamp = $candle[0];
                $low = $candle[1];
                $high = $candle[2];
                $open = $candle[3];
                $close = $candle[4];
                $volume = $candle[5];
            }
        }

        if($responseCode==429){
            echo "Rate Limited!\nSleeping 10 Seconds!\n";
            sleep(10);
            $this->backfill($startDate);
        }elseif($responseCode == 200 && strtotime($endDate) < strtotime('now')){
            echo "Another Call Needed!\n";
            $this->backfill($endDate);
        }elseif($responseCode != 200){
            echo "Unknown Error!\n";
            echo $responseCode." - ".$return['response'];
        }
    }

    private function call($endpoint, $body=null, $extra=null, $pair=null, $method='GET')
    {
        if($pair==null){
            $pair=$this->defaultPair;
        }
        extract($this->endpoints[$endpoint]);
        $uri = sprintf($uri,$pair);
        if(is_array($extra)){
            $append='?';
            foreach($extra as $param=>$value){
                $append.=$param.'='.$value.'&';
            }
            $uri.=$append;
        }else{
            $uri.=$extra;
        }
        $url = $this->url . $uri;
        //echo $url."\n";

        $timestamp = time();
        $sig = base64_encode(hash_hmac('sha256', $timestamp.strtoupper($method).$uri.$body, base64_decode($this->secret),true));
        
        $headers = array(
            'User-Agent: BitBotTrader',
            'Content-Type: application/json',
            'CB-ACCESS-KEY: '.$this->key,
            'CB-ACCESS-SIGN: '.$sig,
            'CB-ACCESS-TIMESTAMP: '.$timestamp,
            'CB-ACCESS-PASSPHRASE: '.$this->passphrase,
        );

        //die(print_r($headers));

        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        );

        $method = strtolower($method);
        if($method == 'get'){
            $options[CURLOPT_HTTPGET] = 1;
        }elseif($method == 'post'){
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $body;
        }elseif($method == 'delete'){
            $options[CURLOPT_CUSTOMEREQUEST] = "DELETE";
        }elseif($method == 'put'){
            $options[CURLOPT_CUSTOMREQUEST] = "PUT";
            $options[CURL_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        if($response === false){
            $error = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
        }

        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($responseCode != 200){
            #print_r($response);
            error_log('GDAX RESPONSE CODE: '. $responseCode . ' - ' . json_decode($response)->message);
        }
        return array('responseCode'=>$responseCode, 'response'=>$response);
    }
}
