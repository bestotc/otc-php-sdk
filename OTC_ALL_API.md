# 签名文档

AccessKeyId，accessKeySecret 找平台获取

## 签名参数
|参数名|描述|默认值|是否参与签名|示例值|
|---|---|---|---|---|
|AccessKeyId|KeyId| |是|3dDnvhpbUP2I|
|accessKeySecret|key| |是|QHqNZXZZ2XRm|
|SignatureVersion|签名版本|1|是|1|
|SignatureMethod|签名方法|HmacSHA256|是|HmacSHA256|
|Timestamp|UTC时间戳| |是|2018-10-26T08:55:45|
|Signature|签名结果| |否| |

## 签名步骤
* 组装参数列表字符串
* 组装待签名字符串
* 签名

### 组装参数列表字符串
* 把参与签名的签名参数加入原始参数列表
* 把新的参数列表的 key 按自然序排序
* 编码值 encodeURIComponent(value)
* 按 key1=value2&key2=values 拼接；

#### 示例：
参数列表：
```
{
    "outOrderNo": "20181026101528gqHSlFJ49BI7dPtNoE",
    "attach": "订单 +编号9527"
}
```
组装结果：
```
AccessKeyId=3dDnvhpbUP2I&SignatureMethod=HmacSHA256&SignatureVersion=1&Timestamp=2018-10-26T08%3A55%3A45&attach=%E8%AE%A2%E5%8D%95%20%2B%E7%BC%96%E5%8F%B79527&outOrderNo=20181026101528gqHSlFJ49BI7dPtNoE
```
说明：
encodeURIComponent(value) 与 javascript encodeURIComponent 函数结果一致，可在浏览器控制台用该函数测试；

### 组装待签名字符串
HTTP方法名大写、请求域名小写、请求URI、组装完的参数列表字符串，用 \n 组装成待签名的字符串；

#### 示例：
```
POST\nvcbotc.tzld.com\n/v1/api/openotc/order/buy\nAccessKeyId=3dDnvhpbUP2I&SignatureMethod=HmacSHA256&SignatureVersion=1&Timestamp=2018-10-26T08%3A55%3A45&attach=%E8%AE%A2%E5%8D%95%20%2B%E7%BC%96%E5%8F%B79527&outOrderNo=20181026101528gqHSlFJ49BI7dPtNoE
```

### 签名
encodeBase64String(HmacSHA256(strToSign, accessKeySecret), "UTF-8")
示例：
```
S4ErItdpKdHF4Rxwss3FZGBWJqkwT3iLzPIxfMrPzMU=
```





# OTC相关接口

## 订单接口列表
| 接口名称 | 请求方法   |  类型  |描述|需要验签
| ---|---| ---- | ---- | ---  |
| 获取账户信息 | /v1/api/openotc/account |POST|获取账户信息|Y|
| 获取币种参考价 | /v1/api/openotc/price |POST|获取币种参考价|Y|
| 买币下单V1 | /v1/api/openotc/order/buy |POST|买币下单V1 |Y|
| 买币下单V2 | /v2/api/openotc/order/buy |POST|买币下单V2|Y|
| 确认付款 | /v1/api/openotc/order/buy/confirmPay |POST|确认付款|Y|
| 撤销订单 | /v1/api/openotc/order/cancel |POST|撤销订单|Y|
| 订单列表 | /v1/api/openotc/order/list |POST|订单列表|Y|
| 批量查询订单列表 | /v1/api/openotc/order/list/batch |POST|批量查询订单列表|Y|
| 订单详情 | /v1/api/openotc/order/detail |POST|订单详情|Y|
| 订单状态异步通知 |  |POST|订单详情|Y|

### 注：所有币种信息 variety
|名称|variety|
|---|----|
|比特币|btc|
|以太坊|eth|
|莱特币|ltc|
|比特现金|bch|
|EDU|edu|
|以太经典|etc|
|USDT|usdt|
### 注：所有法币信息 currency
|Name|currency|
|---|---|
|人民币|CNY|
### 注：付款方式 paymentType
|Name|paymentType|
|---|---|
|银行卡|3|


##获取账户信息
```
POST /v1/api/openotc/account
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|assetList|是|string|资产列表|

### 请求示例:
```

```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "assetList": [
            "variety": "usdt",
            "balance": "1000.00000000",
            "available": "900.00000000",
            "freeze": "100.00000000"
        ]
    }
}
```

##获取币种单价
```
POST /v1/api/openotc/price
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|variety|是|string|币种||btc,usdt...|
|currency|是|string|法币类型||CNY|

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|variety|是|string|币种|
|currency|是|string|法币类型|
|buy|是|string|买币价格|
|sell|是|string|卖币价格|

### 请求示例:
```
{
    "variety": "usdt",
    "currency": "CNY"
}
```
### 响应示例:
```
{
    "code":200,
    "msg":null,
    "body":{
        "variety": "usdt",
        "currency": "CNY",
        "buy": "7.01",
        "sell": "7.01"
    }
}
```

##买币下单V1
```
POST /v1/api/openotc/order/buy
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|variety|是|string|币种||usdt|
|currency|是|string|法币类型||CNY|
|totalAmount|是|string|购买金额|大于0|0-50000|
|paymentType|是|integer|付款方式||3|
|outOrderNo|是|string|第三方订单号|||
|name|否|string|付款用户姓名|||
|idNumber|否|string|付款用户身份证号|||


### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|
|amount|否|string|购买数量|
|payOption|否|object|付款信息|
### 注:status枚举说明(下同)
|类型|描述
|---|----|
|success|下单成功|
|failure|下单失败|
### 注:payOption说明(下同)
|属性名称|是否必须|类型|描述
|---|---|----|---|
|number|是|number|银行卡账号|
|name|是|string|开户人姓名|
|bank|是|string|银行名称|
|bankName|是|string|开户行|

### 请求示例:
```
{
    "variety": "usdt",
    "currency": "CNY",
    "totalAmount": 10000,
    "paymentType": 3,
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
    "name": "张三",
    "idNumber": "41124523"
}
```
### 响应示例:
```
{
    "code":200,
    "msg": null,
    "body":{
        "status": "success",
        "amount": "142.85714286",
        "payOption": {
            "number": "4392267537606583",
            "name": "张三",
            "bank": "招商银行",
            "bankName": "中国招商银行中关村支行"
        }
    }
}
```

##买币下单V2
```
POST /v2/api/openotc/order/buy
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|variety|是|string|币种||usdt|
|currency|是|string|法币类型||CNY|
|totalAmount|是|string|购买金额|大于0|0-50000|
|paymentType|否|string|付款方式,分隔|1,2,3|1,2,3|
|outOrderNo|是|string|第三方订单号|||
|name|是|string|付款用户姓名|||
|idNumber|是|string|付款用户身份证号|||


### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|
|amount|是|string|购买数量|
|totalAmount|是|string|购买金额|
|payOptions|是|List<object>|付款信息列表|
|applyTime|否|string|下单时间|
### 注:status枚举说明(下同)
|类型|描述
|---|----|
|success|下单成功|
|failure|下单失败|
### 注:payOption说明(下同)
|属性名称|是否必须|类型|描述
|---|---|----|---|
|type|是|number|1支付宝2微信3银行卡|
|number|是|number|支付宝账号，银行卡账号|
|name|是|string|支付宝收款人姓名，开户人姓名|
|url|是(1,2)|string|支付宝、微信收款二维码|
|bank|是(3)|string|银行名称|
|bankName|是(3)|string|开户行|

### 请求示例:
```
{
    "variety": "usdt",
    "currency": "CNY",
    "totalAmount": 10000,
    "paymentType": 3,
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
    "name": "张三",
    "idNumber": "41124523"
}
```
### 响应示例:
```
{
    "code":200,
    "msg": null,
    "body":{
        "status": "success",
        "amount": "142.85714286",
        "totalAmount": 10000,
        "applyTime": 1541067368760,
        "payOptions": [
            {
                "type": 3
                "number": "4392267537606583",
                "name": "张三",
                "bank": "招商银行",
                "bankName": "中国招商银行中关村支行"
            },
            {
                "type": 1,
                "number": "4392267537606583",
                "name": "张三",
                "url": "http://www.sample.com/image/2018"
            },
        ]
    }
}
```

##确认付款
```
POST /v1/api/openotc/order/buy/confirmPay
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|第三方订单号|||
|name|是|string|付款用户姓名|||
|idNumber|是|string|付款用户身份证号|||

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|


### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
    "name": "张三",
    "idNumber": "41124523"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```

##撤销订单
```
POST /v1/api/openotc/order/cancel
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|第三方订单号|||

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```

##卖币下单
```
POST /v1/api/openotc/order/sell
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|variety|是|string|币种|usdt|
|currency|是|string|法币类型||CNY|
|totalAmount|否|string|售卖金额|大于0|0-50000|
|amount|否|string|售卖数量|大于0||
|payOptionNumber|是|string|收款人银行卡账号|||
|payOptionName|是|string|收款人姓名|||
|payOptionBank|是|string|收款人银行名称|||
|payOptionBankName|是|string|收款人开户行|||
|outOrderNo|是|string|第三方订单号|||
|name|否|string|付款用户姓名|||
|idNumber|否|string|付款用户身份证号|||

### 注: totalAmount和amount 只能填一项

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|
### 注:status枚举说明(下同)
|类型|描述
|---|----|
|success|下单成功|
|failure|下单失败|
### 请求示例:
```
{
    "variety": "usdt",
    "currency": "CNY",
    "totalAmount": 10000,
    "payOptionNumber": "4392267537606583",
    "payOptionName": "张三",
    "payOptionBank": "招商银行",
    "payOptionBankName": "中国招商银行中关村支行",
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
    "name": "河图",
    "idNumber": "41124523"
}
```
### 响应示例:
```
{
    "code":200,
    "msg": null,
    "body":{
        "status": "success"
    }
}
```

##确认收款
```
POST /v1/api/openotc/order/sell/confirmReceive
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|第三方订单号|||

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|调用状态|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```

##订单列表
```
POST /v1/api/openotc/order/list
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|pageNum|否|integer|页码|1|大于0|
|pageSize|否|integer|每页数量|10|1-2000|
|startTime|否|string|开始时间|||
|endTime|否|string|结束时间|||

### 注：订单status枚举
|status|描述|
|---|---|
|0|全部|
|1|已创建|
|2|待付款|
|3|已付款|
|4|已完成|
|5|取消（已关闭|
### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|total|是|integer|查询总数量|
|list|是|array|订单列表项|
### list属性说明:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|outOrderNo|是|string|第三方订单编号|
|otcOrderNo|是|string|OTC订单编号|
|variety|是|string|币种|
|buyRealName|是|string|卖币订单,卖币人姓名|
|currency|是|string|法币|
|amount|是|string|数量|
|unitPrice|是|string|单价|
|totalAmount|是|string|法币总额|
|paymentType|是|integer|付款方式|
|status|是|integer|订单状态|
|appealStatus|是|integer|申诉状态1无申诉，2申诉中，3处理完成|
|appealTime|是|timestamp|订单申诉时间|
|createTime|是|timestamp|订单创建时间|
|applyTime|是|timestamp|商家接单时间|
|payTime|是|timestamp|买家支付时间|
|confirmTime|是|timestamp|卖家确认收款时间|
|cancelTime|是|timestamp|订单取消时间|
|endPayTime|是|long|付款截止时间倒计时(毫秒)|
|endConfirmTime|是|timestamp|卖币订单,确认付款截止时间|
|payOption|是|object|支付详情|
|type|是|integer|订单类型：2卖币，3买币|


### 请求示例:
```
{
    "pageNum: 1,
    "pageSize": 10,
    "startTime": "2018-10-01T01:39:52",
    "endTime": "2018-11-01T01:39:52"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "total": 100,
        "list":[{
            "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
            "otcOrderNo":"OTC00162167",
            "variety": "usdt",
            "buyRealName":"张三"
            "currency": "CNY",
            "amount": "100",
            "unitPrice": "7.01",
            "totalAmount": "701.00",
            "paymentType": 3,
            "status": 2,
            "appealStatus":1,
            "createTime": 1541067368760,
            "payTime": null,
            "confirmTime": null,
            "cancelTime": null,
            "appealTime":null,
            "endPayTime": null,
            "endConfirmTime": null,
            "applyTime":null,
            "payOption": {
                "number": "4392267537606583",
                "name": "张三",
                "bank": "中国招商银行",
                "bankName": "北京中关村支行"
            },
            "type": 3
        }]
    }
}
```

##批量查询订单列表
```
POST /v1/api/openotc/order/list/batch
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderIds|是|string|第三方订单编号列表,英文","号分割||单次不超过50个编号|

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|outOrderNo|是|string|第三方订单编号|
|otcOrderNo|是|string|OTC订单编号|
|variety|是|string|币种|
|buyRealName|是|string|卖币订单,卖币人姓名|
|currency|是|string|法币|
|amount|是|string|数量|
|unitPrice|是|string|单价|
|totalAmount|是|string|法币总额|
|paymentType|是|integer|付款方式|
|status|是|integer|订单状态|
|appealStatus|是|integer|申诉状态1无申诉，2申诉中，3处理完成|
|appealTime|是|timestamp|订单申诉时间|
|createTime|是|timestamp|订单创建时间|
|applyTime|是|timestamp|商家接单时间|
|payTime|是|timestamp|买家支付时间|
|confirmTime|是|timestamp|卖家确认收款时间|
|cancelTime|是|timestamp|订单取消时间|
|endPayTime|是|long|付款截止时间倒计时(毫秒)|
|endConfirmTime|是|timestamp|卖币订单,确认付款截止时间|
|payOption|是|object|支付详情|
|type|是|integer|订单类型：2卖币，3买币|


### 请求示例:
```
{
    "outOrderIds: "511496733264445440,511495434942808064"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": [{
            "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
            "otcOrderNo":"OTC00162167",
            "variety": "usdt",
            "buyRealName":"张三"
            "currency": "CNY",
            "amount": "100",
            "unitPrice": "7.01",
            "totalAmount": "701.00",
            "paymentType": 3,
            "status": 2,
            "appealStatus":1,
            "createTime": 1541067368760,
            "payTime": null,
            "confirmTime": null,
            "cancelTime": null,
            "appealTime":null,
            "endPayTime": null,
            "endConfirmTime": null,
            "applyTime":null,
            "payOption": {
                "number": "4392267537606583",
                "name": "张三",
                "bank": "中国招商银行",
                "bankName": "北京中关村支行"
            },
            "type": 3
    }]
}
```

##订单详情
```
POST /v1/api/openotc/order/detail
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|第三方订单编号|||

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|outOrderNo|是|string|第三方订单编号|
|otcOrderNo|是|string|OTC订单编号|
|variety|是|string|币种|
|buyRealName|是|string|卖币订单,卖币人姓名|
|currency|是|string|法币|
|amount|是|string|数量|
|unitPrice|是|string|单价|
|totalAmount|是|string|法币总额|
|paymentType|是|integer|付款方式|
|status|是|integer|订单状态|
|appealStatus|是|integer|申诉状态1无申诉，2申诉中，3处理完成|
|appealTime|是|timestamp|订单申诉时间|
|createTime|是|timestamp|订单创建时间|
|applyTime|是|timestamp|商家接单时间|
|payTime|是|timestamp|买家支付时间|
|confirmTime|是|timestamp|卖家确认收款时间|
|cancelTime|是|timestamp|订单取消时间|
|endPayTime|是|long|付款截止时间倒计时(毫秒)|
|endConfirmTime|是|timestamp|卖币订单,确认付款截止时间|
|payOption|是|object|支付详情|
|type|是|integer|订单类型：2卖币，3买币|
### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
        "otcOrderNo":"OTC00162167",
        "variety": "usdt",
        "buyRealName":"张三"
        "currency": "CNY",
        "amount": "100",
        "unitPrice": "7.01",
        "totalAmount": "701.00",
        "paymentType": 3,
        "status": 2,
        "appealStatus":1,
        "createTime": 1541067368760,
        "payTime": null,
        "confirmTime": null,
        "cancelTime": null,
        "appealTime":null,
        "endPayTime": null,
        "endConfirmTime": null,
        "applyTime":null,
        "payOption": {
            "number": "4392267537606583",
            "name": "张三",
            "bank": "中国招商银行",
            "bankName": "北京中关村支行"
        },
        "type": 3
    }
}
```

## 订单状态改变异步通知url:
`post: http://{notifyUrl}`
### 注: notifyUrl 是用户配置[渠道商回调地址][1]
### post 请求类型 Content-Type: application/json;charset=UTF-8

## 请求参数:
|参数 |描述|取值|
|:---|:---|:---|
|subject| 签名| |
|message| 消息体(json格式)，验签对整个消息体签名后，对subject中的签名对比 ||

### message包含:
|属性名 |描述|取值|
|:---|:---|:---|
|outOrderNo| 第三方订单号| |
|status| 订单状态：| 1已创建,2待付款,3已付款,4已完成,5取消(已关闭)|
|appealStatus| 申诉状态：| 1无申诉，2申诉中，3处理完成|
|timestamp| 时间戳|2018-12-18T11:42:10.257Z 0时区时间 |
|type| 类型|OrderStatusChangeMessage|

### 注: 其他参数为队列参数非业务相关,详见于demo

### 响应示例:
```
{
    "subject":"SmH8OzgmheDOHOJGzLVp0/DMA7CIPcEg6Wb0e38mqww=",
    "Message":"{\"appealStatus\":1,\"outOrderNo\":\"order123\",\"status\":3,\"type\":\"OrderStatusChangeMessage\"}",
    "Type":"Notification",
    "MessageId":"c75df859-3524-5b94-ab37-accb95d664e4",
    "TopicArn":"arn:aws:sns:us-east-2:713166642506:T_PAYMENT_CHANNEL_UID_10083",
    "Timestamp":"2018-12-18T07:16:02.808Z",
    "SignatureVersion":"1",
    "Signature":"Lgpd66vL49pL2ExBDcGwUlQ7+g==",
    "SigningCertURL":"https://sns.us-east-2.amazonaws.com/?Action=subscribe",
    "UnsubscribeURL":"https://sns.us-east-2.amazonaws.com/?Action=Unsubscribe"
}
```




## 申诉接口列表
| 接口名称 | 请求方法   |  类型  |描述|需要验签
| ---|---| ---- | ---- | ---  |
| 发起申诉 | /v1/api/openotc/order/appeal/submit |POST| |Y|
| 发送消息 | /v1/api/openotc/order/appeal/message/send |POST| |Y|
| 取申诉详情 | /v1/api/openotc/order/appeal/detail |POST| |Y|
| 取申诉消息列表 | /v1/api/openotc/order/appeal/message/list |POST| |Y|
| 处理申诉 | /v1/api/openotc/order/appealDeal |POST|与当前接口一样|Y|
| 接收新消息推送通知 |  |POST| 推送，需要提供接收推送的URL |Y|


### 注：消息发送者类型 fromType
|类型|fromType|
|---|----|
|第三方客服|0|
|用户|1|
|承兑商|2|
|OTC客服|3|

### 注：申诉状态
|类型|status|
|---|----|
|申诉中|2|
|已处理|3|

### 注：申诉处理状态
|类型|deal_type|
|---|----|
|无操作|0|
|取消付款|1|

##发起申诉
```
POST /v1/api/openotc/order/appeal/submit
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|订单号|---|---|

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|操作成功|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```
### 异常示例:
```
{
    "code": 500,
    "msg": "订单不存在",
}
```

##发送消息
```
POST /v1/api/openotc/order/appeal/message/send
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|订单号|---|---|
|fromType|是|int|消息发送者类型|1|---|
|content|否|string|消息内容|---|---|
|attach|否|string|附件图片URL|---|---|

content, attach 参数二选一必填；

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|操作成功|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16",
    "fromType": 1,
    "content": "已付款",
    "attach": "http://www.sample.com/image/2018"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```
### 异常示例:
```
{
    "code": 500,
    "msg": "订单不存在",
}
```

##取申诉详情
```
POST /v1/api/openotc/order/appeal/detail
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|订单号|---|---|

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|isUser|是|boolean|是否用户发起，否则是承兑商发起|
|status|是|int|申诉状态|
|dealResult|否|string|处理结果|
|dealType|否|int|处理类型|
|createTime|是|string|申诉时间|
|dealTime|否|string|处理时间|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "isUser": true,
        "status": 2,
        "dealResult": "继续交易",
        "dealType": 0,
        "createTime": 1541067368760
    }
}
```
### 异常示例:
```
{
    "code": 500,
    "msg": "订单不存在",
}
```

##取申诉消息列表
```
POST /v1/api/openotc/order/appeal/message/list
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|订单号|---|---|
|lastKey|否|string|取消息列表标识|---|---|

lastKey 首次拉取不需要传，当消息不能一次取完时，接口返回 lastKey，再次取时将该值原样返回请求；
返回的 lastKey == null 表示已取完；
返回的消息列表取最新的消息，再按时间正排序；

### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|lastKey|是|string|取消息列表标识|
|items.content|是|string|消息内容|
|items.attach|否|string|附件图片URL|
|items.fromType|否|int|消息发送者类型|
|items.createTime|是|string|消息发送时间|

### 请求示例:
```
{
    "outOrderNo": "201810291009150NOLpBHPBkwQqhfX16"，
    "lastKey": null
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "lastKey": null,
        "items": [
            {
                "content": "已付款",
                "attach": "http://www.sample.com/image/2018",
                "fromType": 1,
                "createTime": 1541067368760
            },
            {
                "content": "已确认放行",
                "attach": "http://www.sample.com/image/2019",
                "fromType": 2,
                "createTime": 1541067368760
            }
        ]
    }
}
```
### 异常示例:
```
{
    "code": 500,
    "msg": "订单不存在",
}
```

##申诉订单处理
```
POST /v1/api/openotc/order/appealDeal
```
### 请求参数:
|参数名称|是否必须|类型|描述|默认值|取值范围
|---|---|----|----|---|---|
|outOrderNo|是|string|订单号|||
|dealType|是|integer|处理类型 0:继续交易 1:关闭订单|
|remark|是|string|备注|||


### 响应数据:
|参数名称|是否必须|类型|描述
|---|---|----|----|
|status|是|string|是否成功|

### 请求示例:
```
{
    "outOrderNo": "201810291009150",
    "dealType":0,
    "remark": "确认已经打款"
}
```
### 响应示例:
```
{
    "code": 200,
    "msg": null,
    "body": {
        "status": "success"
    }
}
```



##接收新消息推送通知
```
推送，需要提供接收推送的URL
```
###推送内容示例：
```
{
    "timestamp": 1541067368,
    "body": {
        "content": "已确认放行",
        "attach": "http://www.sample.com/image/2019"
    }
}
```
注意：此处的 timestamp 是 unix time，转换后单位是秒；





