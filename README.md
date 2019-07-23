# think-captcha
thinkphp5.1 请求频次类库

## 安装
> composer require topthink/think-throttle


##使用

use think\throttle\ThrottleController;

$res = new Throttle();
if ($res->throttle(frequency, minute, $mobile)){
     return error(1,'同一个手机号一分钟请求1次');
};

