<?php
require_once 'Cainiao.Exception.php';
require_once 'Cainiao.Config.Interface.php';
require_once 'Cainiao.Data.php';

class Cainiao
{
    public static $VERSION = '1.0.0';

    public static $is_product = 1;
    // 正式环境
    public static $product_url = 'http://link.cainiao.com/gateway/link.do';
    // 沙箱环境
    public static $sandbox_url = 'http://linkdaily.tbsandbox.com/gateway/link.do';
    // public static $sandbox_url = 'http://linkdaily.tbsandbox.com/gateway/pac_message_receiver.do';

    /**
     * 电子面单云打印取号接口
     * https://global.link.cainiao.com/?spm=a219a.7386653.0.0.6b56669aePdxLg#/homepage/api/logistics/merchant_electronic_sheet/TMS_WAYBILL_GET?_k=7n8ja4
     * @return [type] [description]
     */
    public static function tmsWaybillGet($config, $inputObj, $timeOut = 6)
    {
        $inputObj->setMsgType('TMS_WAYBILL_GET');
        self::$is_product = $config->GetProductType();

        if (!$inputObj->isCpCodeSet()) {
            throw new CainiaoException("缺少电子面单云打印取号接口必填参数cpCode！");
        } else if (!$inputObj->isSenderSet()) {
            throw new CainiaoException("缺少电子面单云打印取号接口必填参数sender！");
        } else if (!$inputObj->isTradeOrderInfoDtosSet()) {
            throw new CainiaoException("缺少电子面单云打印取号接口必填参数tradeOrderInfoDtos！");
        }

        $inputObj->setLogistic_provider_id($config->GetLogisticProviderId());

        $config->GetProductType() ? $inputObj->toJson() : $inputObj->toXml();
        $inputObj->setData_digest($config);

        $data     = $inputObj->toPostData();
        $response = self::postCurl($data, $timeOut);

        return $response;
    }

    /**
     * 获取发货地，CP开通状态，账户的使用情况
     * https://global.link.cainiao.com/?spm=a219a.7386653.0.0.6b56669aePdxLg#/homepage/api/logistics/merchant_electronic_sheet/TMS_WAYBILL_SUBSCRIPTION_QUERY?_k=3cbd4u
     * @return [type] [description]
     */
    public static function tmsWaybillSubscriptionQuery($config, $inputObj, $timeOut = 6)
    {
        $inputObj->setMsgType('TMS_WAYBILL_SUBSCRIPTION_QUERY');

        $inputObj->setLogistic_provider_id($config->GetLogisticProviderId());

        $config->GetProductType() ? $inputObj->toJson() : $inputObj->toXml();
        $inputObj->setData_digest($config);

        $data     = $inputObj->toPostData();
        $response = self::postCurl($data, $timeOut);

        return $response;
    }

    /**
     * ISV电子面单取消接口
     * https://global.link.cainiao.com/?spm=a219a.7386653.0.0.6b56669aePdxLg#/homepage/api/logistics/merchant_electronic_sheet/TMS_WAYBILL_DISCARD?_k=f8emoz
     * @return [type] [description]
     */
    public static function tmsWaybillDiscard($config, $inputObj, $timeOut = 6)
    {
        $inputObj->setData_digest();

        $json = $inputObj->toJson();

        $response = self::postJsonCurl($json, $timeOut);

        return $response;
    }

    /**
     * 云打印命令行打印渲染接口
     * https://global.link.cainiao.com/?spm=a219a.7386653.0.0.6b56669aePdxLg#/homepage/api/logistics/merchant_electronic_sheet/CLOUDPRINT_CMD_RENDER?_k=z1kfz3
     * @return [type] [description]
     */
    public static function cloudprintCmdRender($config, $inputObj, $timeOut = 6)
    {

    }

    /**
     * 获取云打印标准面单
     * https://global.link.cainiao.com/?spm=a219a.7386653.0.0.6b56669aePdxLg#/homepage/api/logistics/merchant_electronic_sheet/CLOUDPRINT_STANDARD_TEMPLATES?_k=1vvtdl
     * @return [type] [description]
     */
    public static function cloudprintStandardTemplates($config, $inputObj, $timeOut = 6)
    {
        $inputObj->setMsgType('CLOUDPRINT_STANDARD_TEMPLATES');

        $inputObj->setLogistic_provider_id($config->GetLogisticProviderId());

        $inputObj->toJson();
        $inputObj->setData_digest($config);

        $data     = $inputObj->toPostData();
        $response = self::postCurl($data, $timeOut);

        return $response;
    }

    public static function postCurl($data, $second = 30)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        // 需要获取的 URL 地址，也可以在curl_init() 初始化会话的时候。
        $url = self::$is_product ? self::$product_url : self::$sandbox_url;
        curl_setopt($ch, CURLOPT_URL, $url);

        // TRUE 会输出所有的信息，写入到STDERR，或在CURLOPT_STDERR中指定的文件。
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // 当 HTTP 状态码大于等于 400，TRUE 将显示错误详情。 默认情况下将返回页面，忽略 HTTP 代码。
        curl_setopt($ch, CURLOPT_FAILONERROR, false);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        // TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // 设置 HTTP 头字段的数组。格式： array('Content-type: text/plain', 'Content-length: 100')
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded']);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种。
        curl_setopt($ch, CURLOPT_POST, 1);

        $output = curl_exec($ch);
        if ($output) {
            curl_close($ch);
            return $output;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new CainiaoException("curl出错，错误码:$error");
        }
    }
}
