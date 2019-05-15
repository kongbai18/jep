<?php


class Wxpay
{
    /**
     * 微信支付统一下单
     * @param $wxpay_no
     * @param $total_fee
     * @param $trade_type
     * @param $openid
     * @return bool|mixed
     */
    public function unifiedOrder($wxpay_no,$total_fee,$trade_type='NATIVE',$openid='')
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),//公众账户ID
            'mch_id' => config('wxpay.mch_id'),//商户号
            'attach' => '某某分店等信息',//附加数据
            'body' => '腾讯充值中心-QQ会员充值',//商品描述
            'nonce_str' => $nonceStr,//随机字符串
            'sign' => 'MD5',//加密方式默认MD5可以省略
            'notify_url' => base_url() . 'notice',  // 异步通知地址，支付成功后微信支付异步回调地址
            'out_trade_no' => $wxpay_no,//商户订单号；如果有修改价格的需求建议表中单独设立微信支付编号；注意保证唯一性
            'spbill_create_ip' => \request()->ip(),//终端IP
            'total_fee' => $total_fee * 100, // 价格:单位分，默认币种人民币
            'trade_type' => $trade_type,
        ];

        //如果为JSAPI支付
        if($trade_type == 'JSAPI'){
            $params['openid'] = $openid;
        }

        // 生成签名
        $params['sign'] = $this->makeSign($params);

        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->postXmlCurl($this->toXml($params), $url);//发出请求
        $prepay = $this->fromXml($result);//格式转换

        // 请求失败;只有返回数据return_code和result_code都为SUCCESS统一下单才成功
        if ($prepay['return_code'] !== 'SUCCESS' || $prepay['result_code'] !== 'SUCCESS') {
            return false;
        }

        return $prepay;
    }


    /**
     * 微信支付订单状态查询
     * @param $wxpay_no
     * @return mixed
     */
    public function orderQuery($wxpay_no){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),//公众账户ID
            'mch_id' => config('wxpay.mch_id'),//商户号
            'nonce_str' => $nonceStr,
            'out_trade_no' => $wxpay_no,
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $result = $this->postXmlCurl($this->toXml($params), $url);
        $prepay = $this->fromXml($result);

        return $prepay;
    }

    /**
     * 关闭微信支付订单
     * @param $wxpay_no
     * @return mixed
     */
    public function closeOrder($wxpay_no){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),//公众账户ID
            'mch_id' => config('wxpay.mch_id'),//商户号
            'nonce_str' => $nonceStr,
            'out_trade_no' => $wxpay_no,
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        $result = $this->postXmlCurl($this->toXml($params), $url);
        $prepay = $this->fromXml($result);

        return $prepay;
    }


    /**
     * 微信支付申请退款
     * @param $wxpay_no
     * @param $out_refund_no
     * @param $total_fee
     * @param $refund_fee
     * @return mixed
     */
    public function refund($wxpay_no,$out_refund_no,$total_fee,$refund_fee){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),//公众账户ID
            'mch_id' => config('wxpay.mch_id'),//商户号
            'nonce_str' => $nonceStr,//随机字符串
            'out_trade_no' => $wxpay_no,//微信支付号
            'out_refund_no' => $out_refund_no,//退款编号
            'total_fee' => $total_fee * 100,//单号总金额
            'refund_fee' => $refund_fee * 100,//退款金额，不能超过总金额
            'notify_url' =>  base_url() . 'refundNoticy',//退款成功通知地址
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);

        // 请求API
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $result = $this->postXmlCurl($this->toXml($params), $url,true);
        $prepay = $this->fromXml($result);

        return $prepay;
    }


    /**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key='.config('wxpay.key');//支付商户账户获取key
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    /**
     * 生成支付签名
     * @param $nonceStr
     * @param $prepay_id
     * @param $timeStamp
     * @return string
     */
    private function makePaySign($nonceStr, $prepay_id, $timeStamp)
    {
        $data = [
            'appId' => config('wxpay.app_id'),
            'nonceStr' => $nonceStr,
            'package' => 'prepay_id=' . $prepay_id,//统一下单返回的prepay_id
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,
        ];

        //签名步骤一：按字典序排序参数
        ksort($data);

        $string = $this->toUrlParams($data);

        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . config('wxpay.key');

        //签名步骤三：MD5加密
        $string = md5($string);

        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        return $result;
    }


    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * post请求
     * @param $xml
     * @param $url
     * @param bool $is_cert是否需要证书验证
     * @param int $second
     * @return mixed
     */
    private function postXmlCurl($xml, $url,$is_cert = false, $second = 30)
    {
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        if($is_cert){
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, 'apiclient_cert.pem');//证书的物理绝对路径
            //默认格式为PEM，可以注释
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, 'apiclient_key.pem');//证书的物理绝对路径
        }

        // 运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;

    }

    /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }

        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}