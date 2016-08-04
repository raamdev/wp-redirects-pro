<?php
/**
 * Install utils.
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
 * Install utils.
 *
 * @since 160804.29493 Initial release.
 */
class Installer extends SCoreClasses\SCore\Base\Core
{
    /**
     * Other install routines.
     *
     * @since 160804.29493 Initial release.
     */
    public function onOtherInstallRoutines()
    {
        $this->addCaps();
    }

    /**
     * Add capabilities.
     *
     * @since 160804.29493 Initial release.
     */
    protected function addCaps()
    {
        $caps = a::postTypeCaps();

        foreach (['administrator', 'editor', 'shop_manager'] as $_role) {
            if (!($_WP_Role = get_role($_role))) {
                continue; // Not possible.
            }
            foreach ($caps as $_cap) {
                $_WP_Role->add_cap($_cap);
            } // unset($_cap);
        } // unset($_role, $_WP_Role);
    }
}
