<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

function pr($data = null, $die = true)
{
    $trace = debug_backtrace();
    $caller = array_shift($trace);
    echo '<pre>';
    echo "called by [" . $caller['file'] . "] line: " . $caller['line'] . "\n";
    var_dump($data);
    echo '</pre>';
    if ($die) {
        exit;
    }
}
/**
 * @package Yithems
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

    <?php do_action( 'yit_primary' ) ?>

<?php get_footer(); ?>

