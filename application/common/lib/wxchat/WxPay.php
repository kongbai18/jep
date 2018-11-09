<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31 0031
 * Time: 16:00
 */

namespace app\common\lib\wxchat;

use app\common\lib\exception\ApiException;


class WxPay
{
    /**
     * 统一下单API
     * @param $order_no
     * @param $openid
     * @param $total_fee
     * @return array
     * @throws BaseException
     */
    public function unifiedorder($order_no, $openid, $total_fee,$trade_type)
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $openid);
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),
            'attach' => 'test',
            'body' => $order_no,
            'mch_id' => config('wxpay.mch_id'),
            'nonce_str' => $nonceStr,
            'notify_url' => base_url() . 'notice.php',  // 异步通知地址
            'out_trade_no' => $order_no,
            'spbill_create_ip' => \request()->ip(),
            'total_fee' => $total_fee * 100, // 价格:单位分
            'trade_type' => $trade_type,
        ];

        if($trade_type == 'JSAPI'){
            $params['openid'] = $openid;
        }
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->postXmlCurl($this->toXml($params), $url);
        $prepay = $this->fromXml($result);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            throw new ApiException($prepay['return_msg'],200,config('code.error'));
        }
        if ($prepay['result_code'] === 'FAIL') {
            throw new ApiException($prepay['err_code_des'],200,config('code.error'));
        }

        if($trade_type == 'JSAPI'){
            // 生成 nonce_str 供前端使用
            $paySign = $this->makePaySign($params['nonce_str'], $prepay['prepay_id'], $time);
            return [
                'prepay_id' => $prepay['prepay_id'],
                'nonceStr' => $nonceStr,
                'timeStamp' => (string)$time,
                'paySign' => $paySign
            ];
        }

        return $prepay;

    }

    public function orderquery($order_no){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time);
        // API参数
        $params = [
            'appid' => config('wxpay.appid'),
            'mch_id' => config('wxpay.mch_id'),
            'nonce_str' => $nonceStr,
            'out_trade_no' => $order_no,
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
     * 生成paySign
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
            'package' => 'prepay_id=' . $prepay_id,
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
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' .config('wxpay.key');
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
     * 以post方式提交xml到对应的接口url
     * @param $xml
     * @param $url
     * @param int $second
     * @return mixed
     */
    private function postXmlCurl($xml, $url, $second = 30)
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