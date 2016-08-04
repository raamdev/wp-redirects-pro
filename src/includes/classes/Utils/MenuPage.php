<?php
/**
 * Menu page utils.
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
 * Menu page utils.
 *
 * @since 16xxxx Initial release.
 */
class MenuPage extends SCoreClasses\SCore\Base\Core
{
    /**
     * Adds menu pages.
     *
     * @since 16xxxx Initial release.
     */
    public function onAdminMenu()
    {
        s::addMenuPageItem([
            'menu_title'    => __('Options', 'wp-redirects'),
            'parent_page'   => 'edit.php?post_type=redirect',
            'template_file' => 'admin/menu-pages/options/default.php',

            'tabs' => [
                'default' => sprintf(__('%1$s', 'wp-redirects'), esc_html($this->App->Config->©brand['©name'])),
                'restore' => [
                    'label' => __('Restore Default Options', 'wp-redirects'),
                    'url'   => s::restoreDefaultOptionsUrl(), 'onclick' => 'confirm',
                ],
            ],
        ]);
    }
}
