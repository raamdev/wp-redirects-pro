<?php
/**
 * Post meta box utils.
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
 * Post meta box utils.
 *
 * @since 16xxxx Initial release.
 */
class PostMetaBox extends SCoreClasses\SCore\Base\Core
{
    /**
     * On admin init.
     *
     * @since 16xxxx Initial release.
     */
    public function onAdminInit()
    {
        s::addPostMetaBox([
            'include_post_types' => 'redirect',
            'slug'               => 'redirect-data',
            'title'              => __('Redirect Data', 'wp-redirects'),
            'template_file'      => 'admin/menu-pages/post-meta-box/redirect-data.php',
        ]);
    }
}
