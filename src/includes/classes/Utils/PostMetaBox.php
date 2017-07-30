<?php
/**
 * Post meta box utils.
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
 * Post meta box utils.
 *
 * @since 160804.29493 Initial release.
 */
class PostMetaBox extends SCoreClasses\SCore\Base\Core
{
    /**
     * On admin init.
     *
     * @since 160804.29493 Initial release.
     */
    public function onAdminInit()
    {
        s::addPostMetaBox([
            'context'            => 'normal',
            'include_post_types' => 'redirect',
            'slug'               => 'redirect-data',
            'title'              => __('Redirect Data', 'wp-redirects'),
            'template_file'      => 'admin/menu-pages/post-meta-box/redirect-data.php',

            'on_save' => function (int $id, array $data) {
                $this->cacheRegexPatterns();
                $this->checkStats($id);
            },
        ]);
    }

    /**
     * Cache all regex patterns.
     *
     * @since 170730.42995 Initial release.
     */
    public function cacheRegexPatterns()
    {
        $wpDb     = s::wpDb();
        $patterns = []; // Initialize.

        $sql = /* SQL query. */ '
            SELECT
                `post`.`ID` AS `id`,
                `meta`.`meta_value` AS `regex`
            FROM
                `'.$wpDb->posts.'` AS `post`,
                `'.$wpDb->postmeta.'` AS `meta`
            WHERE
                `post`.`post_type` = \'redirect\'
                AND `post`.`post_status` = \'publish\'
                AND `meta`.`post_id` = `post`.`ID`

                AND `meta`.`meta_key` = %s
                AND `meta`.`meta_value` != \'\'
        ';
        $wpDb->prepare($sql, s::postMetaKey('_regex'));

        foreach ($wpDb->get_results($sql) ?: [] as $_r) {
            @preg_match('#'.$_r->regex.'#ui', 'foo://');

            if (!preg_last_error()) {
                $patterns[$_r->regex] = (int) $_r->id;
            } // Only cache valid regex patterns.
        } // unset($_r); // Housekeeping.

        s::sysOption('regex_patterns', $patterns);
    }

    /**
     * Check statistics.
     *
     * @since 170730.42995 Initial release.
     */
    public function checkStats(int $id)
    {
        if (!s::postMetaExists($id, '_hits')) {
            s::updatePostMeta($id, '_hits', 0);
        }
        if (!s::postMetaExists($id, '_last_access')) {
            s::updatePostMeta($id, '_last_access', 0);
        }
    }
}
