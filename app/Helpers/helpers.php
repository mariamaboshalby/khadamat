<?php

use Spatie\MediaLibrary\MediaCollections\Models\Media;

if (!function_exists('media_url')) {
    /**
     * Build a host-relative URL for a Spatie Media item (or any absolute URL string).
     *
     * Spatie generates absolute URLs using APP_URL, which breaks images when the
     * app is opened through a different host/port than APP_URL. Returning a
     * root-relative path (/storage/...) makes media load on any host.
     */
    function media_url($media): ?string
    {
        if ($media instanceof Media) {
            $url = $media->getUrl();
        } else {
            $url = (string) $media;
        }

        if ($url === '') {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        if ($path === null || $path === false) {
            return $url;
        }

        // Collapse duplicate slashes (e.g. from a trailing slash in APP_URL)
        $path = preg_replace('#/+#', '/', $path);

        $query = parse_url($url, PHP_URL_QUERY);

        return $path . ($query ? '?' . $query : '');
    }
}
