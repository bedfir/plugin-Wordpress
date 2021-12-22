<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.dinya.fr
 * @since             1.0.0
 * @package           Numero_chance
 *
 * @wordpress-plugin
 * Plugin Name:       numero_chance
 * Plugin URI:        https://www.dinya.fr
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Firas None
 * Author URI:        https://www.dinya.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       numero_chance
 * Domain Path:       /languages
 */



register_activation_hook(__FILE__, 'numero_chance_activate');
register_deactivation_hook(__FILE__, 'numero_chance_deactivate');
register_uninstall_hook(__FILE__, 'numero_chance_uninstall');


/**
 * Callback d'activation du plugin
 */
function numero_chance_activate()
{
    /**
     * Création, si nécessaire, de la 'WP option' en base de données avec une valeur par défaut
     * @link https://developer.wordpress.org/reference/functions/add_option/
     */
    add_option('numero_chance', 0);
}
/**
 * Callback deactivate du plugin
 */
function numero_chance_deactivate()
{
}
/**
 * Callback uninstall du plugin
 */
function numero_chance_uninstall()
{
    delete_option('numero_chance');
}

if (is_admin()) {
    /**
     * Enregistrement du callback sur le hook 'ajout du menu d'admin'
     */
    add_action('admin_menu', 'numero_chance_add_menu');

    function numero_chance_add_menu()
    {
        add_menu_page('Numero Chance', 'Numero Chance', 'manage_options', 'numero_chance', 'admin_menu_page');
    }
    /**
     *  Afficher admin menu page 
     */


    function admin_menu_page()
    {
        echo "You are viewing the WordPress Administration Panels";
        $updated = false;
        /**
         * Controller de la page d'admin
         * @link https://wordpress.stackexchange.com/questions/7812/what-is-the-recommended-way-to-create-plugin-administration-forms
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

            /**
             * Vérification du nonce
             * @link https://developer.wordpress.org/reference/functions/wp_nonce_field/
             */
            if (isset($_POST['jeton']) && wp_verify_nonce($_POST['jeton'], 'numero_chance_admin_nonce')) {

                // Vérification du la présence de l'attribut de la requête
                if (isset($_POST['cnum']) && !empty($_POST['cnum'])) {

                    // Récupération du numero de chance
                    $cnum = $_POST['cnum'];

                    /**
                     * Mise à jour du numero de chance dans la table MySQL WP Options
                     * @link https://developer.wordpress.org/reference/functions/update_option/
                     */
                    $updated = update_option('numero_chance', $cnum);
                }
            }
        } else {

            /**
             * Récupération du numero de chance depuis la table MySQL WP Options
             * @link https://developer.wordpress.org/reference/functions/get_option/
             */
            $cnum = get_option('numero_chance');
        }
        /**
         * Formulaire HTML avec pour cible la page d'admin du plugin AVEC SECURISATION CSRF
         * @link https://developer.wordpress.org/reference/functions/menu_page_url/
         * @link https://developer.wordpress.org/reference/functions/plugin_dir_url/
         * @link https://developer.wordpress.org/reference/functions/wp_nonce_field/
         */
        echo '<form action="' . menu_page_url('numero_chance', false) . '" method="post">
                <label for="cnum">Saisissez le Numero Chance (0 a 9) :</label>
                <input min="0" max="9" type="number" id="cnum" name="cnum"><br><br>
                <input type="submit" name="submit" value="Enregistrer">'
            . wp_nonce_field('numero_chance_admin_nonce', 'jeton') .
            '</form>';
        if ($updated) {
            echo "Votre changement a bien été enrigistrer !<br> le nouveau Numero de chance = " . $cnum;
        }
    }
} else {
    // Enregistrement du Hook script 
    add_action('wp_enqueue_scripts', 'ava_test_init');

    function ava_test_init()
    {
        wp_enqueue_script('script-js', plugins_url('/script.js', __FILE__), [], false, true);
    }

    function wpse_load_plugin_css()
    {
        $plugin_url = plugin_dir_url(__FILE__);

        wp_enqueue_style('style', $plugin_url . 'style.css');
    }
    // Suppression du style TODO
    add_action('wp_enqueue_scripts', 'wpse_load_plugin_css');





    // ----------- Zone d'affichage sur la Home page
    add_action('wp_body_open', 'wpdoc_add_custom_body_open_code');
    function wpdoc_add_custom_body_open_code()
    {
        if (is_front_page()) {
            //Récupération du numero de chance depuis la table MySQL WP Options
            $numChance = get_option('numero_chance');

            // Fenêtre popup intitulée Jour de chance.
?>
            <div id="modal_back">
                <div id="modal_numero_chance">
                    <p id="numc-msg">Jour de Chance</p>
                    <div id="nav">
                        <select type select id="items">
                            <option value=""> Numero Chance ? </option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                        <input type="hidden" id="numChance" name="numero_chance" value="<?= $numChance ?>">
                        <div>
                            <button class="btn" onclick="jouer()">Jouer</button>
                            <button class="btn" onclick="refuser()">Je refuse</button>
                        </div>
                    </div>
                    <div id="toggle">
                        <div id="output"></div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
