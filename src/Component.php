<?php
/**
 * Template hierarchy class.
 *
 * The framework has its own template hierarchy that can be used instead of the
 * default WordPress template hierarchy.  It is not much different than the
 * default.  It was built to extend the default by making it smarter and more
 * flexible.  The goal is to give theme developers and end users an easy-to-override
 * system that doesn't involve massive amounts of conditional tags within files.
 *
 * @package   Backdrop
 * @author    Benjamin Lu <benlumia007@gmail.com>
 * @copyright 2019-2023. Benjamin Lu
 * @link      https://github.com/benlumia007/backdrop-template-hierarchy
 * @license   https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Define namespace
 */
namespace Backdrop\Template\Hierarchy;

use Backdrop\Template\Hierarchy\Contracts\Hierarchy;
use function Backdrop\Template\Helpers\filter_templates;
use WP_User;

/**
 * Overwrites the core WP template hierarchy.
 *
 * @since  1.0.0
 * @access public
 */
class Component implements Hierarchy {
    /**
     * Array of template types in WordPress Core.
     *
     * @since  1.0.0
     * @access protected
     * @var    array
     */
    protected $types = [
        '404',
        'archive',
        'attachment',
        'author',
        'category',
        'date',
        'embed',
        'frontpage',
        'home',
        'index',
        'page',
        'paged',
        'privacypolicy',
        'search',
        'single',
        'singular',
        'tag',
        'taxonomy',
    ];

    /**
     * Copy of the located template found when running through
     * the tamplate hierarchy.
     *
     * @since  1.0.0
     * @access protected
     * @var    string
     */
    protected $located = '';

    /**
     * An array of the entire template hierarchy for the current page view.
     * This hierarchy does not have the `.php` file name extension.
     */
    protected $hierarchy = [];

    /**
     * Setup the template hierarchy filters.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function boot(): void {

        // Filter the front page template.
        add_filter( 'frontpage_template_hierarchy',  [ $this, 'frontPage' ], 5 );

        add_filter( 'single_template_hierarchy',     [ $this, 'single' ], 5 );

        // System to capture template hierarchy.
        foreach( $this->types as $type ) {

            // Capture the template hierarchy for each type.
            add_filter( "{$type}_template_hierarchy", [ $this, 'templateHierarchy' ], PHP_INT_MAX );

            // Capture the located template.
            add_filter( "{$type}_template", [ $this, 'template' ], PHP_INT_MAX );
        }

        // Re-add the located template.
        add_filter( 'template_include', [ $this, 'templateInclude' ], PHP_INT_MAX );
    }

    /**
     * Returns the full template hierarchy for the current page load.
     *
     * @since  1.0.0
     * @access public
     * @return array
     */
    public function hierarchy(): array {
        return $this->hierarchy;
    }

    /**
     * Fix for the front page template handling in WordPress core. Its
     * handling is not logical because it forces devs to account for both a
     * page on the front page and posts on the front page.  Theme devs must
     * handle both scenarios if they've created a "front-page.php" template.
     * This filter overwrites that and disables the `front-page.php` template
     * if posts are to be shown on the front page.  This way, the
     * `front-page.php` template will only ever be used if an actual page is
     * supposed to be shown on the front.
     *
     * Additionally, this filter allows the user to override the front page
     * via the standard page template.  User choice should always trump
     * developer choice.
     *
     * @since  1.0.0
     * @access public
     * @param  array   $templates
     * @return array
     */
    public function frontPage( array $templates ): array {

        $templates = [];

        if ( ! is_home() ) {

            $custom = get_page_template_slug( get_queried_object_id() );

            if ( $custom ) {
                $templates[] = $custom;
            }

            $templates[] = 'front-page.php';
        }

        // Return the template hierarchy.
        return $templates;
    }

	/**
	 * Overrides the default single (singular post) template for all post
	 * types, including pages and attachments.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $templates
	 * @return array
	 */
	public function single( $templates ): array {

		$templates = [];

		// Get the queried post.
		$post = get_queried_object();

		// Decode the post name.
		$name = urldecode( $post->post_name );

		// Check for a custom post template.
		$custom = get_page_template_slug( $post->ID );

		if ( $custom ) {
			$templates[] = $custom;
		}

		// Add a template based off the post type name.
		$templates[] = "single-{$post->post_type}.php";
		$templates[] = "{$post->post_type}.php";

		// Allow for WP standard 'single' template.
		$templates[] = 'single.php';

		// Return the template hierarchy.
		return $templates;
	}

    /**
     * Filters a queried template hierarchy for each type of template
     * and looks templates within `resources/views'.
     *
     * @since  1.0.0
     * @access public
     * @param $templates;
     * @return array
     */
    public function templateHierarchy( $templates ): array {
        /**
         * Merge the current template's hierarchy with the overall hierarchy array.
         */
        $this->hierarchy = array_merge(
            $this->hierarchy,
            array_map( function( $template ) {

                // Strip extension from file name.
                return substr(
                    $template,
                    0,
                    strlen( $template ) - strlen( strrchr( $template, '.' ) )
                );

            }, $templates )
        );

        // Make sure there are no duplicates in the hierarchy.
        $this->hierarchy = array_unique( $this->hierarchy );

        return filter_templates( $templates );
    }

    /**
     * Filters the template for each type of template in the hierarchy. If
     * `$template` exists, it means we've located a template. So, we're going
     * to store that template for later use and return an empty string so
     * that the template hierarchy continues processing. That way, we can
     * capture the entire hierarchy.
     *
     * @since  1.0.0
     * @access public
     * @param  string  $template
     * @return string
     */
    public function template( string $template ): string {

        if ( ! $this->located && $template ) {
            $this->located = $template;
        }

        return '';
    }

    /**
     * Filter on `template_include` to make sure we fall back to our
     * located template from earlier.
     *
     * @since  1.0.0
     * @access public
     * @param  string  $template
     * @return string
     */
    public function templateInclude( string $template ): string {

        // If there's a template, return it. Otherwise, return our
        // located template from earlier.
        return $template ?: $this->located;
    }
}