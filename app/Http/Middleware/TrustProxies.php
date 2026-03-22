<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = self::HEADER_X_FORWARDED_FOR |
        self::HEADER_X_FORWARDED_HOST |
        self::HEADER_X_FORWARDED_PORT |
        self::HEADER_X_FORWARDED_PROTO |
        self::HEADER_X_FORWARDED_AWS_ELB;
}
