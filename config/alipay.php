<?php
return   array (
    //应用ID,您的APPID。
    'app_id' => "2016101800713143",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIIEowIBAAKCAQEAiDJ/ascf5r3m0PSOh9NV9QKgGnuxzSxn26t2CMQDpuO6bYOcCt+VOC6D6ZrkI+zBdzzCKnwzhe1Q3iasSBYACqVNnidRCgyC4A5s5exFiKFVdnYqg7cX5yn0JeKLFN9YwsdJ1Ij9vafeQAA7XBvqQzJZ/Sy/TqnCk/Z4JCM+LSZyd8xBMfKDUvmfAbed21RgBxDTkY5UdZWY46QzGyhljoSGvVQE1bd1DgWs1haoSR9XWSW/bcEuCh1opV3lJc5KXJ1jwbUBKIrtSXxV+TYkZmy2PYfD/at277EEHf3l/5Wz9LQaJvx4Y1kctPvoL/V5e5GF8L3o7cKZknKVBCe6QQIDAQABAoIBAAytzsjM8r5TTWqEacRhOy5M6USt4TZ9fMmpY5qbTM+7gX5ycxNcDxMk2Wjk33osrzH/eP/lghJkrlOP/BfV3HkhPGGNvXCXC5P6I88fuLVod4FIrNqIL/R4Jl9kgJgX1raDQO9FNYHzX7BLlTsU6jEJPfFgugqDqTaHClYq5b3kJF8ufIkMN8CWss3W+sUslwG6imSiuz8keatMH1bx8EPjOU0Z+M8c+tkr0pK9bPpJTLPvgtVkFmyKRdhizgSXPjqzqxdg4G1+nHGF1u7qW1mtWEUXSupdaP3+FTNupSaUXwgjm5zpJzx7qrK6F2PhV6XXwi44wD72i2MbXvDGt+UCgYEA0jgr9C2lt4YTiMNoXeydLlExSj0g0ktBThppz7c/JMG3aoiuU+QYFq4z4gJEAdvlNcpYfyqBEiNApqSgpP5WmNXqDzVbK8uIJ34UpXFBY7PSEbuebIon6Zav1VIU9mhEirs2t/XD8tRdBZebhcBZJz9ynVT66EaDgE10GTRIWLMCgYEApduOhYPT6rD56urxhtbIeWhD+///k8tCQdDqYpcXURaBR6m0gcbfsglv3hsHku1cv7JKjeS3k+yKKek21a8oBLGhVoS3KU90vVAdG19Th6wSMJhAs+d6hAP9ZfPnkZcrVEEkRS0I2T4hmGDGDKuVSqyZbovyD/sXjrBsNTqCEzsCgYBEQWbMFpIq/4aSLlhRvsTeSBCH+UcR4Mtob50Ri38eESPbnFt186Z6nr9Lz86DCAHpMDUBvSxBVl7kfKmISOiXIUWpAHsEwkYIBKDxgs9nbvhzN4bd2RHSf1HCIq+ZvgsbPGQs4MhprNgzO6GMkQKhgrFVMkVxa4vA+eULVfVzjwKBgDi2Jzh+CBMiqoHqDeYGQQxKW5yqPzDA+onYrpxBf4aJhHEFlSQe5VRSJ78xTNivctxVUWrNPvxEkEWeadKNDsj0F++md56XfZpR73HxmXU1oA6fsNgTGXBUkOuwh4jfwMAReh5xNKpbtU8OIAorDWQ4OkpzbbSLLdXre0SEGgALAoGBAIqD4EsA/7vxF69mbTihTh2xvQDbPMhlhXCSdYK4iPeT6AMgx4jORp1DJMY2uz8uXGDbYK1aKWqpwlG346Fsiubnq/RVf3nMgP5p47ogyyIpIyS5X1/265khnhIhaWqMZdmEwhZzV6Dqua4iUd1XtIPKG9Eq1mbxjzyP6C1DZhx+",

    //异步通知地址
    'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",

    //同步跳转
    'return_url' => "http://localhost/alipay/return_url.php",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgM2HH7bxcY8AT0ISFf0lmDNx8q2IvbDAWwBLuuxZfkwHHkQL8yMAZkDkYNgQIVUZZk8MUd8Z+DUUok3kjqipONyKG2CNbG8c0y6DSnEeE0cMvez2J8fXSqrL+z3IVS2onpUvw3pqgSWcFseAbp927K8C53Hj2q9V5E4qUJ0ekMf1PX2vsk/p7IjF3LeLjGGBTPSHvYY+Dn3A/aoD944Y28f9HImKUKyQWW9nzJ2zvY3T3UcjzEK68+6D4tubiW9X5zlAp2SC4p2yhy5m3le7sBeQbBw6Mf88jL2xzslhTEINZ/LhD2TmAtFs/8opWPZWhBfTTmgGw9t6SLgOGhKu7wIDAQAB",


);