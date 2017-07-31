<?php
/**
 * Template.
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

if (!defined('WPINC')) {
    exit('Do NOT access this file directly.');
}
$Form = $this->s::menuPageForm('§save-options');
?>
<?= $Form->openTag(); ?>
    <?= $Form->openTable(
        __('Regex Options', 'wp-redirects'),
        sprintf(__('<strong>WARNING:</strong> Changing any of these global options may negatively impact existing regex patterns.', 'wp-redirects'), esc_url(s::brandUrl('/kb')))
    ); ?>

        <?= $Form->selectRow([
            'label' => __('Regex Tests', 'wp-redirects'),
            'tip'   => __('If you create regex patterns in some Redirects, what do you want to test in those patterns?', 'wp-redirects'),

            'name'    => 'regex_tests',
            'value'   => s::getOption('regex_tests'),
            'options' => [
                'url'         => __('Full URL (scheme://host/path/?query)', 'wp-redirects'),
                'request_uri' => __('Request URI (i.e. /path/?query, no scheme://host)', 'wp-redirects'),
                'path'        => __('Path Only (i.e. /path, no trailing slash or query)', 'wp-redirects'),
            ],
        ]); ?>

        <?= $Form->inputRow([
            'label' => __('Opening Delimiter', 'wp-redirects'),
            'tip'   => __('Opening delimiter that is used to process regex patterns.<hr /> Defauts to <code>/^</code><hr />If you set this to an empty string, your patterns can have their own opening delimiter.', 'wp-redirects'),

            'name'    => 'regex_open_delim',
            'value'   => s::getOption('regex_open_delim'),

            'class' => '-monospace',
            'attrs' => 'spellcheck="false"',
        ]); ?>

        <?= $Form->inputRow([
            'label' => __('Closing Delimiter/Flags', 'wp-redirects'),
            'tip'   => __('Closing delimiter/flags that is used to process regex patterns.<hr /> Defauts to <code>$/ui</code><hr />If set to an empty string, your patterns can have their own closing delimiter/flags.', 'wp-redirects'),

            'name'    => 'regex_close_delim',
            'value'   => s::getOption('regex_close_delim'),

            'class' => '-monospace',
            'attrs' => 'spellcheck="false"',
        ]); ?>

    <?= $Form->closeTable(); ?>

    <?= $Form->submitButton(); ?>
<?= $Form->closeTag(); ?>
