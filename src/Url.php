<?php
namespace php_fundamentals\url;

use League\Url\Url as LeagueUrl;
use Psr\Http\Message\UriInterface;

class Url implements UriInterface {
    use UrlFactory;

    /** @var LeagueUrl wrapped / adapted league/Url object */
    private $url;

    public function __construct (LeagueUrl $url = null) {
        if ($url === null) {
            $url = new LeagueUrl();
        }
        $this->setUrl($url);
    }

    /**
     * @return LeagueUrl
     */
    private function getUrl () {
        return $this->url;
    }

    /**
     * @param LeagueUrl $url
     */
    private function setUrl (LeagueUrl $url) {
        $this->url = $url;
    }

    /**
     * Retrieve the URI scheme.
     *
     * Implementations SHOULD restrict values to "http", "https", or an empty
     * string but MAY accommodate other schemes if required.
     *
     * If no scheme is present, this method MUST return an empty string.
     *
     * The string returned MUST omit the trailing "://" delimiter if present.
     *
     * @return string The scheme of the URI.
     */
    public function getScheme () {
        return $this->getUrl()
                    ->getScheme()
                    ->__toString();
    }

    /**
     * Retrieve the authority portion of the URI.
     *
     * The authority portion of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * This method MUST return an empty string if no authority information is
     * present.
     *
     * @return string Authority portion of the URI, in "[user-info@]host[:port]"
     *     format.
     */
    public function getAuthority () {
        return $this->getUrl()
                    ->getUserInfo().$this->getHost().$this->getUrl()
                                                          ->getPort()
                                                          ->getUriComponent();
    }

    /**
     * Retrieve the user information portion of the URI, if present.
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     * Implementations MUST NOT return the "@" suffix when returning this value.
     *
     * @return string User information portion of the URI, if present, in
     *     "username[:password]" format.
     */
    public function getUserInfo () {
        return $this->getUrl()
                    ->getUser()
                   ->getUriComponent().$this->getUrl()
                                            ->getPass()
                                            ->getUriComponent();
    }

    /**
     * Retrieve the host segment of the URI.
     *
     * This method MUST return a string; if no host segment is present, an
     * empty string MUST be returned.
     *
     * @return string Host segment of the URI.
     */
    public function getHost () {
        return $this->getUrl()
                    ->getHost()
                    ->getUriComponent();
    }

    /**
     * Retrieve the port segment of the URI.
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     *
     * @return null|int The port for the URI.
     */
    public function getPort () {
        return $this->getUrl()
                    ->getPort()
                    ->__toString();
    }

    /**
     * Retrieve the path segment of the URI.
     *
     * This method MUST return a string; if no path is present it MUST return
     * an empty string.
     *
     * @return string The path segment of the URI.
     */
    public function getPath () {
        return $this->getUrl()
                    ->getPath()
                    ->__toString();
    }

    /**
     * Retrieve the query string of the URI.
     *
     * This method MUST return a string; if no query string is present, it MUST
     * return an empty string.
     *
     * The string returned MUST omit the leading "?" character.
     *
     * @return string The URI query string.
     */
    public function getQuery () {
        return $this->getUrl()
                    ->getQuery()
                    ->__toString();
    }

    /**
     * Retrieve the fragment segment of the URI.
     *
     * This method MUST return a string; if no fragment is present, it MUST
     * return an empty string.
     *
     * The string returned MUST omit the leading "#" character.
     *
     * @return string The URI fragment.
     */
    public function getFragment () {
        return $this->getUrl()
                    ->getFragment()
                    ->__toString();
    }

    /**
     * Create a new instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified scheme. If the scheme
     * provided includes the "://" delimiter, it MUST be removed.
     *
     * Implementations SHOULD restrict values to "http", "https", or an empty
     * string but MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     *
     * @return self A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme ($scheme) {
        $url = $this->getUrl()
                    ->withScheme($scheme);

        return new static($url);
    }

    /**
     * Create a new instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string      $user     User name to use for authority.
     * @param null|string $password Password associated with $user.
     *
     * @return self A new instance with the specified user information.
     */
    public function withUserInfo ($user, $password = null) {
        $url = $this->getUrl()
                    ->withUser($user)
                    ->withPass($password);

        return new static($url);
    }

    /**
     * Create a new instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host Hostname to use with the new instance.
     *
     * @return self A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost ($host) {
        $url = $this->getUrl()
                    ->withHost($host);

        return new static($url);
    }

    /**
     * Create a new instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port Port to use with the new instance; a null value
     *                       removes the port information.
     *
     * @return self A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort ($port) {
        $url = $this->getUrl()
                    ->withPort($port);

        return new static($url);
    }

    /**
     * Create a new instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified path.
     *
     * The path MUST be prefixed with "/"; if not, the implementation MAY
     * provide the prefix itself.
     *
     * An empty path value is equivalent to removing the path.
     *
     * @param string $path The path to use with the new instance.
     *
     * @return self A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath ($path) {
        $url = $this->getUrl()
                    ->withPath($path);

        return new static($url);
    }

    /**
     * Create a new instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified query string.
     *
     * If the query string is prefixed by "?", that character MUST be removed.
     * Additionally, the query string SHOULD be parseable by parse_str() in
     * order to be valid.
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @return self A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery ($query) {
        $url = $this->getUrl()
                    ->withQuery($query);

        return new static($url);
    }

    /**
     * Create a new instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * a new instance that contains the specified URI fragment.
     *
     * If the fragment is prefixed by "#", that character MUST be removed.
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The URI fragment to use with the new instance.
     *
     * @return self A new instance with the specified URI fragment.
     */
    public function withFragment ($fragment) {
        $url = $this->getUrl()
                    ->withFragment($fragment);

        return new static($url);
    }

    /**
     * Return the string representation of the URI.
     *
     * Concatenates the various segments of the URI, using the appropriate
     * delimiters:
     *
     * - If a scheme is present, "://" MUST append the value.
     * - If the authority information is present, that value will be
     *   concatenated.
     * - If a path is present, it MUST be prefixed by a "/" character.
     * - If a query string is present, it MUST be prefixed by a "?" character.
     * - If a URI fragment is present, it MUST be prefixed by a "#" character.
     *
     * @return string
     */
    public function __toString () {
        return $this->getUrl()
                    ->__toString();
    }
}