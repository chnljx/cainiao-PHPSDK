<?php
require_once 'lib/Cainiao.Api.php';
require_once 'Cainiao.Config.php';

echo "<pre>";

$cp_code = 'HTKY';

$config = new CainiaoConfig();

// 获取发货地，CP开通状态，账户的使用情况
$input = new CainiaoTmsWaybillSubscriptionQuery();
$input->setCpCode($cp_code);

$data = Cainiao::tmsWaybillSubscriptionQuery($config, $input);
var_dump($data);
