<?php


/*
Plugin Name: BusinessBites Button
Plugin URI: http://businessbites.com
Description: The BusinessBites button allows you to get paid to communicate with your visitors via chat, call and project requests with built in time tracking and automated billing. To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for a BusinessBites account using the form in the center of the <a href="https://www.businessbites.com">Businessbites.com home page</a>, 3) Go to your <a href="admin.php?page=businessbites_button_settings">BusinessBites configuration</a> page, and 4) Enter the email address you signed up with on BusinessBites.com and choose a color for your button.
Author: BusinessBites team
Version: 1.0.2
Author URI: http://businessbites.com
 */

register_activation_hook(__FILE__, 'businessbites_button_install');

function businessbites_button_install()
{

}

$businessbites_button_possible_colors = array(
    'green' => 'Green',
    'gray' => 'Gray',
    'orange' => 'Orange',
    'blue' => 'Blue'
    );

function businessbites_button_code($handle = false)
{
    $options = get_option('businessbites_button_options');
    echo '<script type="text/javascript" src="http://businessbites.com/ajax/MarketingManager/getBanner?expert_email='.$options['businessbites_button_email'].'&color='.$options['businessbites_button_color'].'"></script>';
}

add_action('wp_head', 'businessbites_button_code', 0);


function businessbites_button_section_text()
{
    echo 'Enter your E-Mail address on BusinessBites and select Button color.';
}

function businessbites_button_options_option_email()
{
    $options = get_option('businessbites_button_options');
    echo "<input id='businessbites_button_email' name='businessbites_button_options[businessbites_button_email]' size='40' type='text' value='{$options['businessbites_button_email']}' />";
}

function businessbites_button_options_option_color()
{
    global $businessbites_button_possible_colors;
    $options = get_option('businessbites_button_options');
    $output = '<select id="businessbites_button_color" name="businessbites_button_options[businessbites_button_color]">';
    foreach($businessbites_button_possible_colors as $k => $v) {
        $output .= '<option value="'.$k.'"'.($k == $options['businessbites_button_color']?' selected="selected"':'').'>'.$v.'</option>';
    }
    $output .= '</select>';
    echo $output;
}

function businessbites_button_options_validate($input)
{
    global $businessbites_button_possible_colors;
    $output['businessbites_button_email'] = trim($input['businessbites_button_email']);
    $output['businessbites_button_color'] = trim($input['businessbites_button_color']);
    if(!array_key_exists($output['businessbites_button_color'], $businessbites_button_possible_colors))
        $output['businessbites_button_color'] = '';

    return $output;
}


function businessbites_button_admin_init(){
    register_setting( 'businessbites_button_options', 'businessbites_button_options', 'businessbites_button_options_validate' );
    add_settings_section('businessbites_button_main', '', 'businessbites_button_section_text', 'businessbites_button');
    add_settings_field('businessbites_button_email', 'BusinessBites E-Mail', 'businessbites_button_options_option_email', 'businessbites_button', 'businessbites_button_main');
    add_settings_field('businessbites_button_color', 'BusinessBites Button Color', 'businessbites_button_options_option_color', 'businessbites_button', 'businessbites_button_main');
}

add_action('admin_init', 'businessbites_button_admin_init');



function businessbites_button_admin_menu()
{
	add_options_page('BusinessBites Button Settings', 'BusinessBites', 'manage_options', 'businessbites_button_settings', 'businessbites_button_settings_page');
}

add_action('admin_menu', 'businessbites_button_admin_menu');


function businessbites_button_settings_page()
{
?>
    <div class="wrap">
        <h2>BusinessBites Button Settings</h2>
        <form action="options.php" method="post">
        <?php settings_fields('businessbites_button_options'); ?>
        <?php do_settings_sections('businessbites_button'); ?>

        <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>

<?php
}
