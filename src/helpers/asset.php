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
}

