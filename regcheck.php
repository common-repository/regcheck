<?php
/*
Plugin Name: Regcheck
Plugin URI: http://autozone.templines.org/dealer/regcheck/
Description: Regcheck
Author: Templines
Version: 1.0.2
Author URI: templines.com
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


/**
 * LPU_Regcheck
 */
class LPU_Regcheck
{
    private static $username;
    //all strings class
    public static $strings = array();
    //all settings class
    public static $settings = array();

    private static $regcheck_lang = array();

    private static $all_api = array();

    function __construct()
    {
        $this->init();

        $lang = get_user_locale();
        if(file_exists(dirname(__FILE__) . '/languages/regcheck_' . $lang . '.php')){
            self::$regcheck_lang = include_once dirname(__FILE__) . '/languages/regcheck_' . $lang . '.php';
        } else {
            self::$regcheck_lang = include_once dirname(__FILE__) . '/languages/regcheck_en_US.php';
        }

        if(file_exists(dirname(__FILE__) . '/countries.php')){
            self::$all_api = include_once dirname(__FILE__) . '/countries.php';
        }

        add_action('plugins_loaded', array(&$this, 'plugin_textdomain'));
        add_action('wp_enqueue_scripts', array(&$this, 'styles_method'));
        add_action('admin_menu', array(&$this, 'add_sub_page_menu_admin'));
        add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
        add_action('admin_enqueue_scripts', array(&$this, 'admin_styles_method'));
    }

    public static function styles_method()
    {
        wp_register_style('regcheck-css', plugin_dir_url(__FILE__) . 'css/regcheck.css');
        wp_enqueue_style('regcheck-css');
    }

    public static function admin_styles_method()
    {
        wp_register_style('regcheck-admin-css', plugin_dir_url(__FILE__) . 'css/regcheck-admin.css');
    }


    public static function plugin_action_links($links, $file)
    {
        if ($file == plugin_basename(__FILE__)) {
            $lpu_links = '<a href="' . get_admin_url() . 'tools.php?page=regcheck-car-option">' . self::$strings['page_plugins_title'] . '</a>';
            array_unshift($links, $lpu_links);
        }
        return $links;
    }

    public static function init()
    {
        self::$settings = array(
            'slug_nameuser' => 'lpu_nameuser',
            'all_keys' => [
                'nameuser',
                'is_ABICode',
                'is_Description',
                'is_RegistrationYear',
                'is_CarMake',
                'is_CarModel',
                'is_EngineSize',
                'is_FuelType',
                'is_MakeDescription',
                'is_ModelDescription',
                'is_Immobiliser',
                'is_NumberOfSeats',
                'is_DriverSide',
                'is_Transmission',
                'is_NumberOfDoors',
                'is_ImageUrl',
                'is_VehicleInsuranceGroup',
                'is_VehicleInsuranceGroupOutOf',
                'is_VehicleIdentificationNumber',
                'is_EngineCode',
                'is_EngineNumber',
                'is_BodyStyle',
                'is_Colour',
                'is_RegistrationDate',
                'is_Cnit',
                'is_Model',
                'is_Year',
                'is_Country',
                'site_key',
                'private_key',
                'country',
            ],
        );
        // Load class strings.
        self::$strings = array(
            'save' => __('Save', 'lpu_regcheck'),
            'form_button' => __('Get', 'lpu_regcheck'),
            'page_plugins_title' => __('Settings', 'lpu_regcheck'),
            'admin_page' => [
                'general_block_title' => __('Name User', 'lpu_regcheck'),
            ],
            'fron_page' => [
                'number' => __('Registration Number', 'lpu_regcheck'),
                'vin' => __('VIN', 'lpu_regcheck'),

                'is_ABICode',
                'is_Description',
                'is_RegistrationYear',
                'is_CarMake',
                'is_CarModel',
                'is_EngineSize',
                'is_FuelType',
                'is_MakeDescription',
                'is_ModelDescription',
                'is_Immobiliser',
                'is_NumberOfSeats',
                'is_DriverSide',
                'is_Transmission',
                'is_NumberOfDoors',
                'is_ImageUrl',
                'is_VehicleInsuranceGroup',
                'is_VehicleInsuranceGroupOutOf',
                'is_VehicleIdentificationNumber',
                'is_EngineCode',
                'is_EngineNumber',
                'is_BodyStyle',
                'is_Colour',
                'is_RegistrationDate',
                'is_Cnit',
                'is_Model',
                'is_Year',
                'is_Country',
                'fields' => [
                    'ABICode' => __('ABI Code', 'lpu_regcheck'),
                    'Description' => __('Description', 'lpu_regcheck'),
                    'RegistrationYear' => __('Registration Year', 'lpu_regcheck'),
                    'CarMake' => __('Car Make', 'lpu_regcheck'),
                    'CarModel' => __('Car Model', 'lpu_regcheck'),
                    'EngineSize' => __('Engine Size', 'lpu_regcheck'),
                    'FuelType' => __('Fuel Type', 'lpu_regcheck'),
                    'MakeDescription' => __('Make Description', 'lpu_regcheck'),
                    'ModelDescription' => __('Model Description', 'lpu_regcheck'),
                    'Immobiliser' => __('Immobiliser', 'lpu_regcheck'),
                    'NumberOfSeats' => __('Number Of Seats', 'lpu_regcheck'),
                    'DriverSide' => __('Driver Side', 'lpu_regcheck'),
                    'Transmission' => __('Transmission', 'lpu_regcheck'),
                    'NumberOfDoors' => __('Number Of Doors', 'lpu_regcheck'),
                    'VehicleInsuranceGroup' => __('VehicleInsurance Group', 'lpu_regcheck'),
                    'VehicleInsuranceGroupOutOf' => __('Vehicle Insurance Group Out Of', 'lpu_regcheck'),
                    'VehicleIdentificationNumber' => __('VehicleIdentification Number', 'lpu_regcheck'),
                    'EngineCode' => __('Engine Code', 'lpu_regcheck'),
                    'EngineNumber' => __('Engine Number', 'lpu_regcheck'),
                    'BodyStyle' => __('Body Style', 'lpu_regcheck'),
                    'Colour' => __('Colour', 'lpu_regcheck'),
                    'RegistrationDate' => __('Registration Date', 'lpu_regcheck'),
                    'Cnit' => __('Cnit', 'lpu_regcheck'),
                    'Model' => __('Model', 'lpu_regcheck'),
                    'Year' => __('Year', 'lpu_regcheck'),
                ],

            ],
        );
    }

    public static function plugin_textdomain()
    {
        load_plugin_textdomain('lpu_regcheck', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public static function get_general_settings()
    {
        return get_option('lpu_general_settings', []);
    }

    public static function get_setting($key, $default = '')
    {
        if (!in_array($key, self::$settings['all_keys'])) {
            return;
        }
        $general = self::get_general_settings();
        if (!empty($general[$key])) {
            return $general[$key];
        } else {
            return $default;
        }
    }

    public static function update_general_settings($key, $val)
    {
        if (!in_array($key, self::$settings['all_keys'])) {
            return;
        }

        $lpu_general_settings = self::get_general_settings();
        $lpu_general_settings[$key] = $val;
        update_option('lpu_general_settings', $lpu_general_settings, 0);
    }

    public static function update_settings_fields()
    {
        if (!empty($_POST['submit']) && $_POST['submit'] == 'up_settings' && isset($_POST['settings'])) {
            foreach ($_POST['settings'] as $key => $value) {
                self::update_general_settings($key, $value);
            }
        }
    }

    function add_sub_page_menu_admin()
    {
        add_submenu_page('tools.php', 'Regcheck', 'Regcheck', 'manage_options', 'regcheck-car-option', array(&$this, 'submenu_page__settings'));
    }

    public function display_all_fields()
    {
        $nameuser = self::get_setting('nameuser');
        $all_keys = self::$settings['all_keys'];
        $site_key = self::get_setting('site_key');
        $private_key = self::get_setting('private_key');
        $country = self::get_setting('country');
        ?>

        <div class="col-reg-field">
            <table>
                <tr>
                    <td>
                        <label for="settings[nameuser]" class="col-lg-1 regcheck-control-label">
                            <?php echo self::$strings['admin_page']['general_block_title']; ?>
                        </label>
                    </td>
                    <td>
                        <input id="settings[nameuser]" name="settings[nameuser]" class="regcheck-form-control"
                               value="<?php echo esc_html($nameuser); ?>">
                        <span><a target="_blank" href="http://www.regcheck.org.uk/?a=16719">regcheck.org.uk</a></span>
                    </td>
                </tr>

                <?php if(count(self::$all_api) > 0):?>
                    <tr>
                        <td>
                            <label for="settings[country]" class="col-lg-1 regcheck-control-label">
                                <?php echo self::$regcheck_lang['choose_country']; ?>
                            </label>
                        </td>
                        <td>
                            <select id="settings[country]" name="settings[country]" class="regcheck-form-control">
                                <?php foreach (self::$all_api as $key => $item):?>
                                    <option <?= ($country == $key) ? 'selected' : ''?> value="<?= $key?>"><?= $key . ' (' . $item . ')'?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                <?php endif;?>

                <tr>
                    <td colspan="2">
                        <h3 class="col-lg-1"><?php echo self::$regcheck_lang['captcha_title']; ?></h3>
						<span><a target="_blank" href="https://developers.google.com/recaptcha/docs/invisible">https://developers.google.com/recaptcha/docs/invisible</a></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="settings[site_key]" class="col-lg-1 regcheck-control-label">
                            <?php echo self::$regcheck_lang['captcha_title_site_key']; ?></label>
                    </td>
                    <td>
                        <input id="settings[site_key]" name="settings[site_key]" class="regcheck-form-control form-key"
                               value="<?php echo $site_key ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="settings[private_key]" class="col-lg-1 regcheck-control-label">
                            <?php echo self::$regcheck_lang['captcha_title_priv_key']; ?></label>
                    </td>
                    <td>
                        <input id="settings[private_key]" name="settings[private_key]" class="regcheck-form-control form-key"
                               value="<?php echo $private_key; ?>">
                    </td>
                </tr>
            </table>
        </div>

        <?php foreach ($all_keys as $key => $opt_name):
            if ($opt_name === 'nameuser' || $opt_name === 'site_key' || $opt_name === 'private_key' || $opt_name === 'country') {
                continue;
            } ?>
            <div class="regcheck-options">
                <div class="col-reg-option">
                    <input type="hidden" name="settings[<?php echo esc_html($opt_name); ?>]" value="">
                    <input type="checkbox" name="settings[<?php echo esc_html($opt_name); ?>]"
                           value="1" <?php checked(1, self::get_setting($opt_name)) ?> />
                    <label><?php echo self::$regcheck_lang[$opt_name]; ?></label>

                </div>
            </div>
        <?php endforeach ?>
        <?php
    }

    public function submenu_page__settings()
    {
        wp_enqueue_style('regcheck-admin-css');

        self::update_settings_fields();
        ?>

        <div class="ad-preview-img-container">
            <h2>Auto Themes</h2>

            <a href="https://templines.com/portfolio/autozone/">
                <img width="350" src="<?php echo plugin_dir_url(__FILE__) . 'css/img/one.jpg';?>">
            </a>
            <a href="https://templines.com/portfolio/autoimage-automotive-car-dealer-wordpress-theme/">
                <img width="350" src="<?php echo plugin_dir_url(__FILE__) . 'css/img/two.jpg';?>">
            </a>
            <a href="https://templines.com/portfolio/autlines-automotive-wordpress-theme/">
                <img width="350" src="<?php echo plugin_dir_url(__FILE__) . 'css/img/three.jpg';?>">
            </a>
        </div>



        <div class="regcheck-wrap">

            <div class="regcheck-settings">
                <div class="regcheck-block">

                    <h3><?php echo get_admin_page_title() ?></h3>

                    <form method="post" class="regcheck-form-horizontal" role="form">
                        <input type="hidden" name="action" value="save">

                        <div class="regcheck-form-group">
                            <?php self::display_all_fields(); ?>
                        </div>

                        <button type="submit" name="submit" class="button button-primary"
                                value="up_settings"><?php echo self::$strings['save']; ?></button>
                    </form>
                </div>
            </div>

        </div>
        <?php
    }

    public static function get_car_license_plate($regNumber)
    {
        if (empty($regNumber)) return null;
        $username = self::get_setting('nameuser');
        if (empty($username)) return null;

        $uri = "https://www.regcheck.org.uk";
        $country = self::get_setting('country');
        if(!empty($country)) {
            $uri = self::$all_api[$country];
        }

        $str = $uri . "/api/reg.asmx/Check?RegistrationNumber=" . $regNumber . "&username=" . $username;
        $xmlData = @file_get_contents($str);
        $xml = simplexml_load_string($xmlData);
        if (!isset($xml->vehicleJson)) return null;
        $strJson = $xml->vehicleJson;
        $json = json_decode($strJson);
        return $json;
    }

    public static function get_car_license_plate_vin($vin)
    {
        if (empty($vin)) return null;
        $username = self::get_setting('nameuser');
        if (empty($username)) return null;

        $uri = "https://www.regcheck.org.uk";
        $country = self::get_setting('country');
        if(!empty($country)) {
            $uri = self::$all_api[$country];
        }

        $str = $uri . "/api/reg.asmx/VinCheck?Vin=" . $vin . "&username=" . $username;
        $xmlData = @file_get_contents($str);
        $xml = simplexml_load_string($xmlData);
        if (!isset($xml->vehicleJson)) return null;
        $strJson = $xml->vehicleJson;
        $json = json_decode($strJson);
        return $json;
    }

    public static function is_attr_show($attr)
    {
        $all_keys = self::get_general_settings();
        if (!empty($all_keys['is_' . $attr])) {
            return true;
        }
        return false;
    }

    public static function is_image_json($key, $url)
    {
        if (!empty($key) && $key === 'ImageUrl') {
            echo '<img class="regcheck-image" src="' . $url . '">';
            return 1;
        }
    }

    public static function show_result_json($result)
    {
        $html = '';
        if (!empty($result)) {
            $html .= '<dl>';
            foreach ($result as $key => $param) {
                if (!is_object($param)) {
                    if (!self::is_attr_show($key)) continue;
                    if (self::is_image_json($key, $param)) continue;
                    if ($key === '') {

                    }
                    $html .= '<dt>' . $key . '</dt>';
                    $html .= '<dd>' . $param . '</dd>';

                } else {
                    foreach ($param as $key2 => $p_val) {
                        if (!is_object($p_val)) {
                            $str_key = $key2;
                            if ($str_key === 'CurrentTextValue') {
                                $str_key = $key;
                            }
                            if (!self::is_attr_show($str_key)) continue;
                            if (self::is_image_json($key, $p_val)) continue;
                            $html .= '<dt>' . $str_key . '</dt>';
                            $html .= '<dd>' . $p_val . '</dd>';
                        }
                    }
                }
            }
            $html .= '</dl>';
        }
        echo $html;
    }

    public static function show_result_table($result)
    {
        $fields = self::$strings['fron_page']['fields'];
        $html = '';
        if (!empty($result)) {

            foreach ($result as $key => $param) {
                if (!is_object($param)) {
                    if (!self::is_attr_show($key)) continue;
                    if (self::is_image_json($key, $param)) continue;
                    if (isset($fields[$key])) {
                        $key = $fields[$key];
                    }
                    $html .= '<tr>';
                    $html .= '<td><strong>' . $key . '</strong></td>';
                    $html .= '<td><span>' . $param . '</span></td>';
                    $html .= '</tr>';
                } else {
                    foreach ($param as $key2 => $p_val) {
                        if (!is_object($p_val)) {
                            $str_key = $key2;
                            if ($str_key === 'CurrentTextValue') {
                                $str_key = $key;
                            }
                            if (!self::is_attr_show($str_key)) continue;
                            if (self::is_image_json($key, $p_val)) continue;
                            if (isset($fields[$str_key])) {
                                $str_key = $fields[$str_key];
                            }
                            $html .= '<tr>';
                            $html .= '<td><strong>' . $str_key . '</strong></td>';
                            $html .= '<td><span>' . $p_val . '</span></td>';
                            $html .= '</tr>';
                        }
                    }
                }
            }
        }
        $html = '<table class="table table-striped table-condensed" ><thead></thead><tbody>' . $html . '</tbody></table>';
        echo $html;
    }

    public static function show_form_regcheck($atts)
    {
        $form_type = (!empty($atts['vin']) && $atts['vin'] === 'on') ? "vin"
            : ((!empty($atts['number']) && $atts['number'] === 'on') ? 'number' : '');
        if(empty($atts) || empty($form_type)/* || (!empty($_POST['lpu_form_regcheck']) && $_POST['lpu_form_regcheck'] !== $form_type)*/
            /*|| !empty($GLOBALS['regcheck_form'])*/) {
            return;
        }

        /*if(!empty($form_type)) {
            $GLOBALS['regcheck_form'] = $form_type;
        }*/
        $captcha = true;
        $site_key = self::get_setting('site_key');
        $private_key = self::get_setting('private_key');
        $use_recaptcha = !empty($site_key) && !empty($private_key);
        if($use_recaptcha && !empty($_POST['lpu_nameuser']) && !empty($_POST['lpu_form_regcheck'])) {
            if(isset($_POST['g-recaptcha-response'])) {
                require_once(dirname(__FILE__) . '/recaptcha/autoload.php');
                $recaptcha = new \ReCaptcha\ReCaptcha($private_key);
                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if (!$resp->isSuccess()){
                    $captcha = false;
                }
            } else {
                $captcha = false;
            }
        }

        $result = [];
        if ($captcha && !empty($_POST['lpu_form_regcheck'])) {
            if (isset($_POST['lpu_nameuser']) && !empty($_POST['lpu_nameuser'])) {
                $result = esc_attr(self::get_car_license_plate($_POST['lpu_nameuser']), 'lpu_regcheck');
            } elseif (isset($_POST['lpu_vin']) && !empty($_POST['lpu_vin'])) {
                $result = esc_attr(self::get_car_license_plate_vin($_POST['lpu_vin']), 'lpu_regcheck');
            }
        }

        $nameuser = isset($_POST['lpu_nameuser']) ? sanitize_text_field($_POST['lpu_nameuser']) : '';
        $vin = isset($_POST['lpu_vin']) ? sanitize_text_field($_POST['lpu_vin']) : '';
        $atts = shortcode_atts(array(
            'number' => 'off',
            'vin' => 'off'
        ), $atts);
        ?>
        <?php if($atts['vin'] === 'on' || $atts['number'] === 'on'):?>
            <div class="regcheck-block">
                <form name="form-<?= $form_type?>" method="post" role="form">
                    <input type="hidden" name="action" value="save">

                    <?php if ($atts['vin'] === 'on'): ?>
                        <input name="lpu_vin" placeholder="<?php echo self::$strings['fron_page']['vin']; ?>"
                               class="regcheck-input" value="<?php echo esc_html($vin); ?>">
                    <?php elseif ($atts['number'] === 'on'): ?>
                        <input name="lpu_nameuser" placeholder="<?php echo self::$strings['fron_page']['number']; ?>"
                               class="regcheck-input" value="<?php echo esc_html($nameuser); ?>">
                    <?php endif; ?>

                    <?php if($use_recaptcha):?>
                        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
                        <script>
                            var onloadCallback = function() {
                                grecaptcha.execute();
                            };
                            function setResponse(response) {
                                document.getElementById('captcha-response').value = response;
                            }
                        </script>
                        <div class="g-recaptcha" data-sitekey="<?= $site_key?>" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>
                        <?php if(!$captcha):?>
                            <div class="text-danger" id="recaptchaError"><?php echo esc_html(self::$regcheck_lang['captcha_error'],'lpu_regcheck'); ?></div>
                        <?php endif;?>
                        <input type="hidden" id="captcha-response" name="captcha-response" />
                    <?php endif;?>

                    <button type="submit" name="lpu_form_regcheck" class="regcheck-submit-save"
                            value="<?= $form_type?>"><?php echo esc_html(self::$strings['form_button'], 'lpu_regcheck'); ?></button>
                </form>

				<?php if(!empty($_POST['lpu_form_regcheck']) && $_POST['lpu_form_regcheck'] === $form_type):?>
					<div class="regcheck-result">
						<?php esc_attr(self::show_result_table($result), 'lpu_regcheck'); ?>
					</div>
				<?php endif;?>
            </div>
        <?php endif;
    }
}

add_shortcode('regcheck', ['LPU_Regcheck', 'show_form_regcheck']);
$lpu_Regcheck = new LPU_Regcheck();