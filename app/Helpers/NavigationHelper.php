<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('isActiveRoute')) {
    /**
     * Determine if the current route matches any of the given routes.
     *
     * @param array|string $routes
     * @return string
     */
    function isActiveRoute($routes)
    {
        if (is_array($routes)) {
            return in_array(Route::currentRouteName(), $routes) ? 'active' : '';
        }

        return Route::currentRouteName() === $routes ? 'active' : '';
    }
}

if (!function_exists('isActiveCollapse')) {
    /**
     * Determine if the current route is within a collapse menu.
     *
     * @param array|string $routes
     * @return string
     */
    function isActiveCollapse($routes)
    {
        if (is_array($routes)) {
            return in_array(Route::currentRouteName(), $routes) ? 'show' : 'collapsed';
        }

        return Route::currentRouteName() === $routes ? 'show' : 'collapsed';
    }

    /**
     * Determine if the current route matches any of the given patterns.
     *
     * @param array|string $patterns
     * @return string
     */
    function isActiveRoutePattern($patterns)
    {
        foreach ((array) $patterns as $pattern) {
            if (Route::is($pattern)) {
                return 'active';
            }
        }
        return '';
    }

    /**
     * Determine if the current route matches any of the given patterns for collapse.
     *
     * @param array|string $patterns
     * @return string
     */
    function isActiveCollapsePattern($patterns)
    {
        foreach ((array) $patterns as $pattern) {
            if (Route::is($pattern)) {
                return 'show';
            }
        }
        return 'collapsed';
    }
}
