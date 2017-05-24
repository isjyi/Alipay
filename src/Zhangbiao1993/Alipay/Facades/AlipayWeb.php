<?php

namespace Zhangbiao1993\Alipay\Facades;

use Illuminate\Support\Facades\Facade;

class AlipayWeb extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alipay.web';
    }
}
