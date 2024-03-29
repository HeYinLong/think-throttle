<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\throttle;

class ThrottleController
{
    public static function throttle($maxAttempts = 6, $decayMinutes = 1, $key = null)
    {
        $throttle = new Throttle();
        return $throttle->throttle($maxAttempts, $decayMinutes, $key);

    }
}
