<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Requests\Concerns;

use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Helpers\CacheKeyHelper;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;

/**
 * Config-driven response caching for read-only Odoo requests.
 *
 * Apply to a request that also implements {@see Cacheable}.
 * The cache store and TTL come from `config('laravel-odoo.cache')`, mirroring the
 * laravel-docuware package.
 *
 * Two Odoo-specific overrides matter here:
 *  - Odoo reads are POST (`/json/2/<model>/search_read`), so POST is added to the
 *    cacheable methods (the plugin only caches GET/OPTIONS by default).
 *  - The query (domain/fields/limit) travels in the JSON body, which the default cache
 *    key ignores — so the body is folded into the key to avoid collisions between
 *    different queries hitting the same endpoint.
 */
trait HasOdooCaching
{
    use HasCaching;

    public function resolveCacheDriver(): LaravelCacheDriver
    {
        $driver = config('laravel-odoo.cache.driver');

        return new LaravelCacheDriver(Cache::store(is_string($driver) ? $driver : null));
    }

    public function cacheExpiryInSeconds(): int
    {
        $lifetime = config('laravel-odoo.cache.lifetime_in_seconds', 60);

        if (is_int($lifetime)) {
            return $lifetime;
        }

        return is_string($lifetime) && is_numeric($lifetime) ? (int) $lifetime : 60;
    }

    /**
     * Odoo's read endpoints use POST, so POST must be cacheable alongside the defaults.
     *
     * @return array<int, Method>
     */
    protected function getCacheableMethods(): array
    {
        return [Method::GET, Method::OPTIONS, Method::POST];
    }

    /**
     * Fold the JSON body into the cache key so that two POST reads to the same endpoint
     * with different domains/fields/limits do not share a cache entry.
     */
    protected function cacheKey(PendingRequest $pendingRequest): ?string
    {
        $body = $pendingRequest->body()?->all();

        return CacheKeyHelper::create($pendingRequest).'|'.json_encode($body ?: []);
    }
}
