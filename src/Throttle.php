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

namespace think\middleware;

use think\Cache;
use think\facade\Request;

class Throttle
{
    protected $cache;
    protected $ip;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->ip = Request::ip();
    }

    /**
     * 如果不上传$key默认以IP地址做唯一标识，如果多次使用此方法，必须上传不同的唯一标识$key,否者后者将覆盖前者
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @param null $key //缓存标识，此标识为用户唯一认证标识
     * @return bool
     */
    public function throttle($maxAttempts = 6, $decayMinutes = 1, $key = null){
        if ($key == null ) $key = $this->ip;
        return $this->tooManyAttempts($key.':rate', $maxAttempts, $decayMinutes);
    }
    /**
     * @param $key
     * @param $maxAttempts
     * @param int $decayMinutes
     * @return bool
     */
    public  function tooManyAttempts($key, $maxAttempts, $decayMinutes = 1){
        if ($this->attempts($key) >= $maxAttempts) {
            if ($this->cache->has($key.':timer')) {
                return true;
            }
            $this->resetAttempts($key);
        }
        $this->hit($key, $decayMinutes);
        return false;
    }

    /**
     * Increment the counter for a given key for a given decay time.
     *
     * @param  string  $key
     * @param  float|int  $decayMinutes
     * @return int
     */
    public function hit($key, $decayMinutes = 1)
    {
        if (!($this->cache->has($key.':timer'))){
            $this->cache->set($key.':timer', (time() + $decayMinutes*60), $decayMinutes*60);
        }

        $added = $this->cache->get($key);
        $hits = (int)$this->cache->set($key, $added + 1, $decayMinutes*60);

        if (! $added && $hits == 1) {
            $this->cache->set($key, 1, $decayMinutes*60);
        }

        return $hits;
    }

    /**
     * Reset the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function resetAttempts($key)
    {
        return $this->cache->rm($key);
    }

    /**
     * Get the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function attempts($key)
    {
        return $this->cache->get($key, 0);
    }
}
