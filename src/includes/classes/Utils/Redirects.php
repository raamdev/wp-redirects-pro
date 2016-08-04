<?php
/**
 * Redirect utils.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare (strict_types = 1);
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
     * On `template_redirect` hook.
     *
     * @since 16xxxxx Initial release.
     */
    public function onTemplateRedirect()
    {
        if (!is_singular('redirect')) {
            return; // Not applicable.
        }
        $redirect_id = (int) get_the_ID();

        $url = (string) s::getPostMeta($redirect_id, '_url');
        $url = !$url ? home_url('/') : $url;

        $status_code = (string) s::getPostMeta($redirect_id, '_status_code');

        if ($status_code === 'default') {
            $status_code = s::getOption('default_status_code');
        }
        $status_code = (int) $status_code; // Force integer.
        $status_code = !$status_code ? 301 : $status_code;

        wp_redirect($url, $status_code);
        exit; // Stop upon redirecting.
    }
}
