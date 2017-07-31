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
extract($this->vars); // Template variables.
$Form = $this->s::postMetaBoxForm('redirect-data');

switch (s::getOption('regex_tests')) {
    case 'url':
        $regex_tests = __('full URL (<code>scheme://host/path/?query</code>)', 'wp-redirects');
        break;

    case 'request_uri':
        $regex_tests = __('request URI (i.e., <code>/path/?query</code>, no scheme://host)', 'wp-redirects');
        break;

    case 'path':
    default: // Default case.
        $regex_tests = __('path only (i.e. <code>/path</code>, no trailing slash &amp; no query)', 'wp-redirects');
        break;
}
$regex_open_delim  = s::getOption('regex_open_delim');
$regex_close_delim = s::getOption('regex_close_delim');

if ($regex_open_delim && $regex_close_delim) {
    $regex_note = sprintf(__('<code>%1$s</code><strong>pattern</strong><code>%2$s</code> &nbsp;&nbsp; <em>Enter pattern only, excluding <code>%1$s</code> and <code>%2$s</code> delimiters.<br />You\'re testing the %3$s.</em>', 'wp-redirects'), esc_html($regex_open_delim), esc_html($regex_close_delim), $regex_tests);
} elseif ($regex_open_delim) {
    $regex_note = sprintf(__('<code>%1$s</code><strong>pattern</strong> &nbsp;&nbsp; <em>Enter pattern only, excluding <code>%1$s</code> delimiter.<br />You\'re testing the %2$s.</em>', 'wp-redirects'), esc_html($regex_open_delim), $regex_tests);
} elseif ($regex_close_delim) {
    $regex_note = sprintf(__('<strong>pattern</strong><code>%1$s</code> &nbsp;&nbsp; <em>Enter pattern only, excluding <code>%1$s</code> delimiter.<br />You\'re testing the %2$s.</em>', 'wp-redirects'), esc_html($regex_close_delim), $regex_tests);
} else {
    $regex_note = sprintf(__('<strong>pattern</strong> &nbsp;&nbsp; <em>Enter a valid regex pattern, including <code>/.../ui</code> delimiters.<br />You\'re testing the %1$s.</em>', 'wp-redirects'), $regex_tests);
}
?>
<?= $Form->openTable(); ?>

    <?= $Form->inputRow([
        'type'  => 'number',
        'label' => __('Status Code', 'wp-redirects'),
        'tip'   => __('HTTP status code.', 'wp-redirects'),

        'name'  => '_code',
        'value' => s::getPostMeta($post_id, '_code', s::getOption('default_code')),
    ]); ?>

    <?= $Form->inputRow([
        'placeholder' => __('http://..., or a local /request/uri', 'wp-redirects'),
        'label'       => __('Redirect To', 'wp-redirects'),
        'tip'         => __('This is where a visitor will be redirected to.', 'wp-redirects'),

        'name'  => '_url',
        'value' => s::getPostMeta($post_id, '_url'),
    ]); ?>

    <?= $Form->hrRow(); ?>

    <?= $Form->selectRow([
        'label' => __('Force Top', 'wp-redirects'),
        'tip'   => __('If enabled, this breaks out of frames; i.e., when redirecting, the new URL is always loaded as the top-level document.', 'wp-redirects'),

        'name'    => '_top',
        'value'   => s::getPostMeta($post_id, '_top', s::getOption('default_top')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'label' => __('Cacheable', 'wp-redirects'),
        'tip'   => __('Allow a browser to cache the redirection and therefore bypass the original URL on future requests?', 'wp-redirects'),

        'name'    => '_cacheable',
        'value'   => s::getPostMeta($post_id, '_cacheable', s::getOption('default_cacheable')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'label' => __('Fwd. Query', 'wp-redirects'),
        'tip'   => __('Forward query string arguments to new URL?', 'wp-redirects'),

        'name'    => '_forward_query',
        'value'   => s::getPostMeta($post_id, '_forward_query', s::getOption('default_forward_query')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->hrRow(); ?>

    <?= $Form->inputRow([
        'placeholder' => __('no pattern', 'wp-redirects'),
        'label'       => __('Regex Pattern', 'wp-redirects'),

        'tip'  => __('Optionally match any location served by PHP/WordPress; i.e., if any location matches a pattern entered here, it too, will trigger this redirect.', 'wp-redirects'),
        'note' => $regex_note, // Changes, based on configuration options.

        'name'  => '_regex',
        'value' => s::getPostMeta($post_id, '_regex'),

        'class' => '-monospace',
        'attrs' => 'spellcheck="false"',
    ]); ?>

<?= $Form->closeTable(); ?>
