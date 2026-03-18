<?php

declare(strict_types=1);

namespace App\Support\Http;

use Closure;
use Illuminate\Http\Client\Request;
use Psr\Http\Message\RequestInterface;
use Illuminate\Http\Client\PendingRequest;

class HttpClientNamedBeforeSending
{
    /**
     * @param  Closure(Request,array,PendingRequest):(Request|RequestInterface)  $fn
     */
    public function __construct(
        public readonly string $name,
        private readonly Closure $fn
    ) {}

    public function __invoke(Request $request, array $options, PendingRequest $client): null|Request|RequestInterface
    {
        return $this->fn->__invoke($request, $options, $client);
    }
}
