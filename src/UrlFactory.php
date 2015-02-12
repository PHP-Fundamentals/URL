<?php
namespace php_fundamentals\http;

use League\Url\Url as LeagueUrl;

trait UrlFactory {
    /**
     * Return an instance of Url from a string
     *
     * @param string $url a string or an object that implement the __toString method
     *
     * @return Url
     *
     * @throws \RuntimeException
     */
    public static function createFromUrl ($url) {
        $url = LeagueUrl::createFromUrl($url);

        return new Url($url);
    }

    /**
     * Return an instance of Url from a server array
     *
     * @param array $server the server array (normally $_SERVER)
     *
     * @return Url
     *
     * @throws \RuntimeException
     */
    public static function createFromServer (array $server) {
        $url = LeagueUrl::createFromServer($server);

        return new Url($url);
    }
}