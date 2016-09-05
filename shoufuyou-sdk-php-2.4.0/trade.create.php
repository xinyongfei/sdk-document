<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SfyPay.php';

//demo 请款接口
$payInfo = array(
    'merchant_order_id'     => '08391531618453',
    'product_type'          => 'flight',//free_tour | group_tour | local_tour | flight
    'product_name'          => '北京到东京自由行',
    'product_url'           => 'https://m.shoufuyou.com/products/1001363',
    'product_id'            => '1001215',
    'order_amount'          => 2000,
    'periods'               => 12,
    'time_limit'            => 30,
    'tourist_adult_number'  => 1,
    'tourist_kid_number'    => 0,
    'tourist_baby_number'   => 0,
    'departure'             => '北京',
    'arrival'               => '东京',
    'departure_date'        => '2016-03-05',
    'return_date'           => '2016-03-08',
    'hotel_class'           => 4,
    'source_type'           => 'ios',
    'return_url'            => 'http://sdk.sfydev.com/after_pay_return.php',
    'notify_url'            => 'http://sdk.sfydev.com/after_pay_notify.php',
    //'extra_param'           => '测试数据',
    //出行人信息
    'tourists'              => array(
        array(
            'name'                => '何强',
            'name_spelling'       => 'He Qiang',
            'id_card_number'      => '413026198705115879',
            'mobile'              => '18721468348',
            'email'               => 'qiang.he@shoufuyou.com',
        ),
        /*
        array(
            'name'                => '伏天',
            'name_spelling'       => 'Fu Tian',
            'id_card_number'      => '310110198510172775',
            'mobile'              => '13916526716',
            'email'               => 'futian@shoufuyou.com',
        ),*/
    )
);

$sfyPay = new SfyPay();
$html = $sfyPay->buildRequestHtml($payInfo);
echo $html;
?>
