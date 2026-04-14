<?php

namespace Pixelvide\Ops\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AllowPrivateIps
{
    /**
     * Default CIDR ranges that are treated as private.
     *
     * @var array<int, string>
     */
    private const DEFAULT_ALLOWED_IPS = [
        '127.0.0.0/8',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '::1/128',
        'fc00::/7',
        'fe80::/10',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isAllowed($request->ip())) {
            throw new HttpException(403, 'Forbidden');
        }

        return $next($request);
    }

    /**
     * Determine whether the given IP is allowed.
     */
    private function isAllowed(?string $ip): bool
    {
        if (!$ip) {
            return false;
        }

        $allowedIps = config('ops.allowed_ips', self::DEFAULT_ALLOWED_IPS);

        foreach ((array) $allowedIps as $allowedIp) {
            if (IpUtils::checkIp($ip, $allowedIp)) {
                return true;
            }
        }

        return false;
    }
}
