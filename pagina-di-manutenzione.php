<?php
/**
 * Pagina di manutenzione
 *
 * @package       PAGINADIMA
 * @author        Giuseppe Di Chiara
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Pagina di manutenzione
 * Plugin URI:    https://giuseppegabrieledichiara.it/project/maintenance-page
 * Description:   Aggiungi una pagina di manutenzione a scelta tra quelle esistenti. Compatibile con Bricks Builder.
 * Version:       1.0.0
 * Author:        Giuseppe Di Chiara
 * Author URI:    https://giuseppegabrieledichiara.it
 * Text Domain:   pagina-di-manutenzione
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function maintenance_page_options() {
    add_options_page('Maintenance Page', 'Maintenance Page', 'manage_options', 'maintenance-page', 'maintenance_page_options_page');
}
add_action('admin_menu', 'maintenance_page_options');

function maintenance_page_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You cannot access this page. The site is probably under maintenance or you do not have sufficient permissions to view the destination. If you think this is an error, please contact <a href="https://feedback.it">Feedback Srl</a>'));
    }
    $pages = get_pages();
    $selected_page = get_option('maintenance_page');
    ?>
    <div class="wrap">
        <h2>Maintenance Page</h2>
        <form method="post" action="options.php">
            <?php settings_fields('maintenance_page_options'); ?>
            <?php do_settings_sections('maintenance-page'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Maintenance Page</th>
                    <td>
                        <select name="maintenance_page">
                            <?php foreach ($pages as $page) { ?>
                                <option value="<?php echo $page->ID; ?>" <?php selected($selected_page, $page->ID); ?>><?php echo $page->post_title; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function maintenance_page_options_init() {
    register_setting('maintenance_page_options', 'maintenance_page');
}
add_action('admin_init', 'maintenance_page_options_init');

function maintenance_page_redirect() {
    if (!is_admin() && !is_user_logged_in() && !is_page(get_option('maintenance_page'))) {
        wp_redirect(get_permalink(get_option('maintenance_page')), 302);
        exit;
    }
}
add_action('template_redirect', 'maintenance_page_redirect');
