<?php
require_once 'Cainiao.Config.Interface.php';
require_once 'Cainiao.Exception.php';

/**
 *
 * 数据对象基础类，该类中定义数据类最基本的行为
 *
 */
class CainiaoDataBase
{
    protected $values = array();

    public function setLogistic_provider_id($logistic_provider_id)
    {
        $this->values['logistic_provider_id'] = $logistic_provider_id;
    }

    public function getLogistic_provider_id()
    {
        return $this->values['logistic_provider_id'];
    }

    public function isLogistic_provider_idSet()
    {
        return array_key_exists('logistic_provider_id', $this->values);
    }

    /**
     * 设置消息类型
     * @param string $value
     **/
    public function setMsgType($msg_type)
    {
        $this->values['msg_type'] = $msg_type;
    }

    /**
     * 设置消息类型
     **/
    public function isMsgTypeSet()
    {
        return array_key_exists('msg_type', $this->values);
    }

    /**
     * 设置签名
     **/
    public function setData_digest($config)
    {
        $this->values['data_digest'] = base64_encode(md5($this->values['logistics_interface'] . $config->GetAppSecret(), true));
    }

    /**
     * 获取签名
     * @return 值
     **/
    public function getData_digest()
    {
        return $this->values['data_digest'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return true 或 false
     **/
    public function isData_digestSet()
    {
        return array_key_exists('data_digest', $this->values);
    }

    /**
     * 输出json字符
     * @throws CainiaoException
     **/
    public function toJson()
    {
        if (!is_array($this->values['logistics_interface']) || count($this->values['logistics_interface']) <= 0) {
            throw new CainiaoException("数组数据异常！");
        }

        $json = json_encode($this->values['logistics_interface'], JSON_UNESCAPED_UNICODE);

        $this->values['logistics_interface'] = $json;
        return $json;
    }

    /**
     * 将json转为array
     * @param string $json
     * @throws CainiaoException
     */
    public function fromJson($json)
    {
        if (!$json) {
            throw new CainiaoException("json数据异常！");
        }
        //将json转为array
        $this->values = json_decode($json, true);
        return $this->values;
    }

    public function toPostData()
    {
        $arr = [];

        foreach ($this->values as $key => $value) {
            $arr[] = sprintf('%s=%s', $key, $value);
        }

        $data = implode('&', $arr);
        return $data;
    }

    /**
     * 输出xml字符
     * @throws CainiaoException
     **/
    public function toXml()
    {
        if (!is_array($this->values['logistics_interface']) || count($this->values['logistics_interface']) <= 0) {
            throw new CainiaoException("数组数据异常！");
        }

        $xml = "<request>";
        foreach ($this->values['logistics_interface'] as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } elseif (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->array2Xml($val) . "</" . $key . ">";
            } elseif (is_bool($val)) {
                if ($val) {
                    $xml .= "<" . $key . ">true</" . $key . ">";
                } else {
                    $xml .= "<" . $key . ">false</" . $key . ">";
                }
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</request>";

        $this->values['logistics_interface'] = $xml;
        return $xml;
    }

    public function array2Xml($arr)
    {
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } elseif (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->array2Xml($val) . "</" . $key . ">";
            } elseif (is_bool($val)) {
                if ($val) {
                    $xml .= "<" . $key . ">true</" . $key . ">";
                } else {
                    $xml .= "<" . $key . ">false</" . $key . ">";
                }
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }

        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws CainiaoException
     */
    public function fromXml($xml)
    {
        if (!$xml) {
            throw new CainiaoException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 获取设置的值
     */
    public function getValues()
    {
        return $this->values;
    }
}

/**
 * 电子面单云打印取号接口
 */
class CainiaoTmsWaybillGet extends CainiaoDataBase
{
    /**
     * 设置物流公司Code（详见https://support-cnkuaidi.taobao.com/doc.htm#?docId=105085&docType=1）
     * @param string $value
     **/
    public function setCpCode($value)
    {
        $this->values['logistics_interface']['cpCode'] = $value;
    }
    /**
     * 获取物流公司Code
     * @return 值
     **/
    public function getCpCode()
    {
        return $this->values['logistics_interface']['cpCode'];
    }
    /**
     * 判断物流公司Code是否存在
     * @return true 或 false
     **/
    public function isCpCodeSet()
    {
        return array_key_exists('cpCode', $this->values['logistics_interface']);
    }

    public function setStoreCode($value)
    {
        $this->values['logistics_interface']['storeCode'] = $value;
    }
    public function getStoreCode()
    {
        return $this->values['logistics_interface']['storeCode'];
    }
    public function isStoreCodeSet()
    {
        return array_key_exists('storeCode', $this->values['logistics_interface']);
    }

    public function setResourceCode($value)
    {
        $this->values['logistics_interface']['resourceCode'] = $value;
    }
    public function getResourceCode()
    {
        return $this->values['logistics_interface']['resourceCode'];
    }
    public function isResourceCodeSet()
    {
        return array_key_exists('resourceCode', $this->values['logistics_interface']);
    }

    public function setDmsSorting($value)
    {
        $this->values['logistics_interface']['dmsSorting'] = $value;
    }
    public function getDmsSorting()
    {
        return $this->values['logistics_interface']['dmsSorting'];
    }
    public function isDmsSortingSet()
    {
        return array_key_exists('dmsSorting', $this->values['logistics_interface']);
    }

    public function setNeedEncrypt($value)
    {
        $this->values['logistics_interface']['needEncrypt'] = $value;
    }
    public function getNeedEncrypt()
    {
        return $this->values['logistics_interface']['needEncrypt'];
    }
    public function isNeedEncryptSet()
    {
        return array_key_exists('needEncrypt', $this->values['logistics_interface']);
    }

    /**
     * 设置发货人信息
     * @param array $value
     **/
    public function setSender($value)
    {
        if (!is_array($value) || count($value) <= 0) {
            throw new CainiaoException("发货人信息数据异常！");
        }

        if (!array_key_exists('detail', $value['address'])) {
            throw new CainiaoException("缺少电子面单云打印取号接口发货人信息发货地址详细地址参数！");
        }

        if (!array_key_exists('province', $value['address'])) {
            throw new CainiaoException("缺少电子面单云打印取号接口发货人信息发货地址省参数！");
        }

        if (!array_key_exists('name', $value)) {
            throw new CainiaoException("缺少电子面单云打印取号接口发货人信息姓名参数！");
        }

        if (!array_key_exists('mobile', $value) && !array_key_exists('phone', $value)) {
            throw new CainiaoException("缺少电子面单云打印取号接口发货人信息手机号码或固定电话参数！");
        }

        $this->values['logistics_interface']['sender'] = $value;
    }
    /**
     * 获取发货人信息
     * @return 值
     **/
    public function getSender()
    {
        return $this->values['logistics_interface']['sender'];
    }
    /**
     * 判断发货人信息是否存在
     * @return true 或 false
     **/
    public function isSenderSet()
    {
        return array_key_exists('sender', $this->values['logistics_interface']);
    }

    /**
     * 设置请求面单列表
     * @return 值
     **/
    public function setTradeOrderInfoDtos($value)
    {
        if (!is_array($value) || count($value) <= 0) {
            throw new CainiaoException("请求面单列表数据异常！");
        }

        foreach ($value as $k => $v) {
            if (!array_key_exists('logisticsServices', $v)) {
                throw new CainiaoException("缺少请求面单信息请求logisticsServices参数！");
            }

            if (!array_key_exists('objectId', $v)) {
                throw new CainiaoException("缺少请求面单信息请求ID参数！");
            }

            if (!array_key_exists('orderChannelsType', $v['orderInfo'])) {
                throw new CainiaoException("缺少请求面单信息订单渠道平台参数！");
            }

            if (!is_array($v['orderInfo']['tradeOrderList']) || count($v['orderInfo']['tradeOrderList']) <= 0) {
                throw new CainiaoException("订单号列表数据异常！");
            }

            if (!array_key_exists('id', $v['packageInfo'])) {
                throw new CainiaoException("缺少请求面单信息包裹id参数！");
            }

            if (!array_key_exists('items', $v['packageInfo'])) {
                throw new CainiaoException("缺少请求面单信息包裹items参数！");
            }

            if (!array_key_exists('detail', $v['recipient']['address'])) {
                throw new CainiaoException("缺少电子面单云打印取号接口收货人信息收货地址详细地址参数！");
            }

            if (!array_key_exists('province', $v['recipient']['address'])) {
                throw new CainiaoException("缺少电子面单云打印取号接口收货人信息收货地址省参数！");
            }

            if (!array_key_exists('name', $v['recipient'])) {
                throw new CainiaoException("缺少电子面单云打印取号接口收货人信息姓名参数！");
            }

            if (!array_key_exists('mobile', $v['recipient']) && !array_key_exists('phone', $v['recipient'])) {
                throw new CainiaoException("缺少电子面单云打印取号接口收货人信息手机号码或固定电话参数！");
            }

            if (!array_key_exists('templateUrl', $v)) {
                throw new CainiaoException("缺少云打印标准模板URL参数！");
            }

            if (!array_key_exists('userId', $v)) {
                throw new CainiaoException("缺少使用者ID参数！");
            }
        }

        $this->values['logistics_interface']['tradeOrderInfoDtos'] = $value;
    }
    /**
     * 获取请求面单列表
     * @return 值
     **/
    public function getTradeOrderInfoDtos()
    {
        return $this->values['logistics_interface']['tradeOrderInfoDtos'];
    }
    /**
     * 判断请求面单列表是否存在
     * @return true 或 false
     **/
    public function isTradeOrderInfoDtosSet()
    {
        return array_key_exists('tradeOrderInfoDtos', $this->values['logistics_interface']);
    }
}

/**
 * 获取发货地，CP开通状态，账户的使用情况
 */
class CainiaoTmsWaybillSubscriptionQuery extends CainiaoDataBase
{
    /**
     * 设置物流公司Code（详见https://support-cnkuaidi.taobao.com/doc.htm#?docId=105085&docType=1）
     * @param string $value
     **/
    public function setCpCode($value)
    {
        $this->values['logistics_interface']['cpCode'] = $value;
    }
    /**
     * 获取物流公司Code
     * @return 值
     **/
    public function getCpCode()
    {
        return $this->values['logistics_interface']['cpCode'];
    }
    /**
     * 判断物流公司Code是否存在
     * @return true 或 false
     **/
    public function isCpCodeSet()
    {
        return array_key_exists('cpCode', $this->values['logistics_interface']);
    }
}

/**
 * ISV电子面单取消接口
 */
class CainiaoTmsWaybillDiscard extends CainiaoDataBase
{

}

/**
 * 云打印命令行打印渲染接口
 */
class CainiaoCloudprintCmdRender extends CainiaoDataBase
{

}

/**
 * 获取云打印标准面单
 */
class CainiaoCloudprintStandardTemplates extends CainiaoDataBase
{
    /**
     * 设置物流公司Code（详见https://support-cnkuaidi.taobao.com/doc.htm#?docId=105085&docType=1）
     * @param string $value
     **/
    public function setCpCode($value)
    {
        $this->values['logistics_interface']['cpCode'] = $value;
    }
    /**
     * 获取物流公司Code
     * @return 值
     **/
    public function getCpCode()
    {
        return $this->values['logistics_interface']['cpCode'];
    }
    /**
     * 判断物流公司Code是否存在
     * @return true 或 false
     **/
    public function isCpCodeSet()
    {
        return array_key_exists('cpCode', $this->values['logistics_interface']);
    }
}
