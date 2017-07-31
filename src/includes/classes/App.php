<?php
/**
 * Application.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WPRedirects\Pro\Classes;

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
 * Application.
 *
 * @since 160804.29493 Initial release.
 */
class App extends SCoreClasses\App
{
    /**
     * Version.
     *
     * @since 160804.29493
     *
     * @type string Version.
     */
    const VERSION = '170731.4372'; //v//

    /**
     * Constructor.
     *
     * @since 160804.29493 Initial release.
     *
     * @param array $instance Instance args.
     */
    public function __construct(array $instance = [])
    {
        $Core = $GLOBALS[SCoreClasses\App::class];

        $instance_base = [
            '©di' => [
                '©default_rule' => [
                    'new_instances' => [],
                ],
            ],

            '§specs' => [
                '§in_wp'           => false,
                '§is_network_wide' => false,

                '§type' => 'plugin',
                '§file' => dirname(__FILE__, 4).'/plugin.php',
            ],
            '©brand' => [
                '©acronym' => 'WPRD',
                '©name'    => 'WP Redirects',

                '©slug' => 'wp-redirects',
                '©var'  => 'wp_redirects',

                '©short_slug' => 'wp-rd',
                '©short_var'  => 'wp_rd',

                '©text_domain' => 'wp-redirects',
            ],
            '§pro_option_keys' => [
                // Nothing here.
            ],
            '§default_options' => [
                'rewrite_prefix'  => 'r',
                'stats_enable'    => true,

                'default_code'          => 301,
                'default_top'           => false,
                'default_cacheable'     => true,
                'default_forward_query' => false,

                'regex_tests'       => 'path',
                'regex_open_delim'  => '/^',
                'regex_close_delim' => '$/ui',
            ],
        ];
        parent::__construct($instance_base, $instance);
    }

    /**
     * Early hook setup handler.
     *
     * @since 160804.29493 Initial release.
     */
    protected function onSetupEarlyHooks()
    {
        parent::onSetupEarlyHooks();

        s::addAction('other_install_routines', [$this->Utils->Installer, 'onOtherInstallRoutines']);
        s::addAction('other_uninstall_routines', [$this->Utils->Uninstaller, 'onOtherUninstallRoutines']);
    }

    /**
     * Other hook setup handler.
     *
     * @since 160804.29493 Initial release.
     */
    protected function onSetupOtherHooks()
    {
        parent::onSetupOtherHooks();

        add_action('init', [$this->Utils->PostType, 'onInit']);

        if ($this->Wp->is_admin) {
            add_action('admin_init', [$this->Utils->PostMetaBox, 'onAdminInit']);
            add_action('admin_menu', [$this->Utils->MenuPage, 'onAdminMenu']);

            add_action('admin_init', [$this->Utils->PostTypeCols, 'onAdminInit']);
            add_filter('manage_redirect_posts_columns', [$this->Utils->PostTypeCols, 'onColumns']);
            add_filter('manage_edit-redirect_sortable_columns', [$this->Utils->PostTypeCols, 'onSortableColumns']);
            add_action('manage_redirect_posts_custom_column', [$this->Utils->PostTypeCols, 'onColumnValue'], 10, 2);
            add_action('pre_get_posts', [$this->Utils->PostTypeCols, 'onPreGetPosts']);
        } else {
            add_action('wp', [$this->Utils->Redirects, 'onWp'], -1000000);
            // Before security gates in our other plugins.
        }
    }
}
