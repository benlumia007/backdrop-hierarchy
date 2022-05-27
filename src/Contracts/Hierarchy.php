<?php
/**
 * Hierarchy interface
 *
 * @package   Backdrop
 * @author    Benjamin Lu <benlumia007@gmail.com>
 * @copyright 2019-2022. Benjamin Lu
 * @link      https://github.com/benlumia007/backdrop-template-hierarchy
 * @license   https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Define namespace
 * 
 * @since  1.0.0
 * @access public
 */
namespace Benlumia007\Backdrop\Template\Hierarchy\Contracts;
use Benlumia007\Backdrop\Contracts\Bootable;

/**
 * Template Hierarchy Template
 * 
 * @since  1.0.0
 * @access public
 */
interface Hierarchy extends Bootable {
    /**
     * Should return an array of template file name without the file extensions.
     * 
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function hierarchy();
}