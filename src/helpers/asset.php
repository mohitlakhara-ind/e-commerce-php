<?php

if (!function_exists('asset_url')) {
    /**
     * Resolve a web-accessible base path for the /src directory regardless of
     * whether the project is served from a subdirectory.
     */
    function asset_base_path(): string
    {
        static $basePath = null;
        if ($basePath !== null) {
            return $basePath;
        }

        $normalizedDocRoot = '';
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $normalizedDocRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        }

        // This helper file lives in /src/helpers, so the parent directory is /src
        $srcDir = str_replace('\\', '/', dirname(__DIR__));

        if ($normalizedDocRoot && strpos($srcDir, $normalizedDocRoot) === 0) {
            $basePath = substr($srcDir, strlen($normalizedDocRoot));
        } else {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '/');
            $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        }

        if ($basePath === false || $basePath === '/' || $basePath === '\\' || $basePath === '.') {
            $basePath = '';
        } else {
            $basePath = '/' . ltrim($basePath, '/');
        }

        return $basePath;
    }

    function asset_url(string $path): string
    {
        $normalized = ltrim($path, '/');
        $basePath = asset_base_path();
        return ($basePath !== '' ? $basePath : '') . '/' . $normalized;
    }

    function site_url(string $path = ''): string
    {
        $basePath = asset_base_path();
        $trimmedBase = rtrim($basePath, '/');
        $normalized = ltrim($path, '/');

        if ($normalized === '') {
            return $trimmedBase === '' ? '/' : $trimmedBase . '/';
        }

        $prefix = $trimmedBase === '' ? '' : $trimmedBase;
        return ($prefix === '' ? '' : $prefix) . '/' . $normalized;
    }

    function sanitize_redirect_path(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim(str_replace("\0", '', $path));
        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $parts = parse_url($path);
            $path = ($parts['path'] ?? '/');
            if (!empty($parts['query'])) {
                $path .= '?' . $parts['query'];
            }
        }

        if ($path === '' || $path[0] !== '/') {
            return null;
        }

        return $path;
    }

    function resolve_redirect_target(?string $candidate = null, ?string $fallback = null): string
    {
        $target = sanitize_redirect_path($candidate);
        if (!$target) {
            $target = sanitize_redirect_path($fallback);
        }

        if (!$target) {
            $target = site_url();
        }

        return $target;
    }

    function login_url_with_redirect(?string $target = null): string
    {
        $targetPath = sanitize_redirect_path($target);
        if (!$targetPath) {
            $targetPath = sanitize_redirect_path($_SERVER['REQUEST_URI'] ?? null) ?? site_url();
        }

        $loginUrl = site_url('login');
        $separator = strpos($loginUrl, '?') === false ? '?' : '&';

        return $loginUrl . $separator . 'redirect=' . rawurlencode($targetPath);
    }
}

