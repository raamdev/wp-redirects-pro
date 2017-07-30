<?php
/**
 * Redirect utils.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WPRedirects\Pro\Classes\Utils;

use WebSharks\WpSharks\WPRedirects\Pro\Classes;
use WebSharks\WpSharks\WPRedirects\Pro\Interfaces;
use WebSharks\WpSharks\WPRedirects\Pro\Traits;
#
use WebSharks\WpSharks\WPRedirects\Pro\Classes\AppFacades as a;
use WebSharks\WpSharks\WPRedirects\Pro\Classes\SCoreFacades as s;
use WebSharks\WpSharks\WPRedirects\Pro\Classes\CoreFacades as c;
#
use WebSharks\WpSharks\Core\Classes as SCoreClasses;
use WebSharks\WpSharks\Core\Interfaces as SCoreInterfaces;
use WebSharks\WpSharks\Core\Traits as SCoreTraits;
#
use WebSharks\Core\WpSharksCore\Classes as CoreClasses;
use WebSharks\Core\WpSharksCore\Classes\Core\Base\Exception;
use WebSharks\Core\WpSharksCore\Interfaces as CoreInterfaces;
use WebSharks\Core\WpSharksCore\Traits as CoreTraits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Redirect utils.
 *
 * @since 16xxxxx Initial release.
 */
class Redirects extends SCoreClasses\SCore\Base\Core
{
    /**
     * On `wp` request hook.
     *
     * @since 16xxxxx Initial release.
     */
    public function onWp()
    {
        if (is_singular('redirect')) {
            $this->maybeRedirect();
        } else {
            $this->checkPatterns();
        }
    }

    /**
     * Check regex patterns.
     *
     * @since 16xxxxx Initial release.
     */
    protected function checkPatterns()
    {
        $patterns = s::sysOption('regex_patterns');
        $patterns = (array) ($patterns ?: []);

        if (!$patterns || c::isCli()) {
            return; // Nothing to check.
        } elseif (!($url = c::currentUrl())) {
            return; // Not possible.
        }
        foreach ($patterns as $_regex => $_id) {
            if (preg_match('#'.$_regex.'#ui', $url)) {
                $this->maybeRedirect($_id);
            }
        } // unset($_regex, $_id); // Housekeeping.
    }

    /**
     * Maybe perform redirection.
     *
     * @since 16xxxxx Initial release.
     *
     * @param int|null $id Redirect ID.
     */
    protected function maybeRedirect(int $id = null)
    {
        if (!($id = $id ?? (int) get_the_ID())) {
            return; // Not possible.
        } elseif (!($url = (string) s::getPostMeta($id, '_url'))) {
            return; // Not possible.
        }
        if (s::getOption('stats_enable')) {
            $hits = (int) s::getPostMeta($id, '_hits');
            s::updatePostMeta($id, '_hits', $hits + 1);
            s::updatePostMeta($id, '_last_access', time());
        }
        $code          = (int) s::getPostMeta($id, '_code');
        $code          = (int) ($code ?: s::getOption('default_code'));
        $code          = $code ?: 302; // Absolute default status code.

        $top           = (bool) s::getPostMeta($id, '_top');
        $cacheable     = (bool) s::getPostMeta($id, '_cacheable');
        $forward_query = (bool) s::getPostMeta($id, '_forward_query');

        if ($forward_query && $_GET) {
            $url = c::addUrlQueryArgs(c::unslash($_GET), $url);
        }
        if (c::isCli()) {
            exit($code.': '.$url);
        } else {
            status_header($code); // HTTP status.
            c::redirect($url, compact('top', 'cacheable'));
        }
    }
}
