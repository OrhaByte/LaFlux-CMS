<?php

namespace ExtensionsValley\Cms\Middleware;

use Closure;
use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\RedirectRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CmsRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        // Never intercept admin routes
        if (str_starts_with($path, '/admin')) {
            return $next($request);
        }

        if (!Schema::hasTable('cms_redirects')) {
            return $next($request);
        }

        $rule = RedirectRule::where('source_url', $path)
            ->where('status', 1)
            ->first();

        if ($rule) {
            $rule->increment('hits');
            $target = CmsHelper::sanitizeUrl($rule->target_url);
            if (str_starts_with($target, '/') && !str_starts_with($target, '//')) {
                $target = url($target);
            }

            return redirect($target, (int) $rule->status_code ?: 301);
        }

        return $next($request);
    }
}

