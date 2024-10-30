<?php
/**
 * Plugin Name: Minimal Maintenance Mode
 * Plugin URI: https://github.com/StachRedeker/Minimal-Maintenance-Mode
 * Description: The most minimalistic maintenance mode plugin. Enable maintenance mode, show a custom message.
 * Version: 1.0.1
 * Author: Stach Redeker
 * Author URI: https://www.stachredeker.nl
 * License: GPL v3 or later
 * License URI: https://gnu.org/licenses/gpl-3.0.html
 */

// Check if maintenance mode is enabled
function is_maintenance_mode() {
  return get_option('maintenance_mode', false);
}

// Get the secret phrase
function get_secret_phrase() {
  return get_option('maintenance_mode_secret_phrase', '');
}

// FRONT END STUFF
// Render the maintenance mode message
function maintenance_mode_message() {
  $message = get_option('maintenance_mode_message', 'This website is currently undergoing maintenance. Please check back soon.');
  $heading = get_option('maintenance_mode_heading', 'Maintenance Mode');

  // Make the maintence mode page
  echo '
    <html>
    <head>
      <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f5f5f5;
        }

        .maintenance-container {
          max-width: 600px;
          margin: 100px auto;
          padding: 30px;
          background-color: #ffffff;
          border: 1px solid #cccccc;
          text-align: center;
          box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .maintenance-container h1 {
          color: #333333;
          font-size: 24px;
          margin-bottom: 20px;
        }

        .maintenance-container p {
          color: #666666;
        }
      </style>
    </head>
    <body>
      <div class="maintenance-container">
        <h1>' . esc_html($heading) . '</h1>
        <p>' . esc_html($message) . '</p>
      </div>
    </body>
    </html>';

  exit;
}

// BACK END STUFF
// Handle maintenance mode
function maintenance_mode_handler() {
  // Bypass maintenance mode for logged-in users and /wp-admin pages
  if (is_user_logged_in() || strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false) {
    return;
  }

  // Check if maintenance mode is enabled
  if (is_maintenance_mode()) {
    $secret_phrase = get_option('maintenance_mode_secret_phrase', '');

    // Check if the secret phrase is appended to the URL or if the cookie is set with the correct secret phrase
    if (!empty($secret_phrase) && (isset($_GET[$secret_phrase]) || (isset($_COOKIE['maintenance_mode_secret_phrase']) && $_COOKIE['maintenance_mode_secret_phrase'] === $secret_phrase))) {
      // Set a cookie with the secret phrase to bypass maintenance mode
      setcookie('maintenance_mode_secret_phrase', $secret_phrase, time() + 604800, '/');

      return;
    }

    maintenance_mode_message();
  }
}

// Hook the maintenance mode handler function into the wp action
add_action('wp', 'maintenance_mode_handler');

// Add the maintenance mode settings page to the admin menu
function maintenance_mode_settings_page() {
  add_options_page('Maintenance Mode Settings', 'Maintenance Mode', 'manage_options', 'maintenance-mode', 'maintenance_mode_settings');
}
add_action('admin_menu', 'maintenance_mode_settings_page');

// Render the maintenance mode settings page
function maintenance_mode_settings() {
  if (!current_user_can('manage_options')) {
    return;
  }

  // Save settings when the form is submitted
  if (isset($_POST['save_activate'])) {
    update_option('maintenance_mode', true);
    $message = 'Maintenance mode activated successfully.';
  } elseif (isset($_POST['save_deactivate'])) {
    update_option('maintenance_mode', false);
    $message = 'Maintenance mode deactivated successfully.';
  }

  // Save custom heading and message when the form is submitted
  if (isset($_POST['maintenance_mode_heading'])) {
    $maintenance_mode_heading = sanitize_text_field($_POST['maintenance_mode_heading']);
    update_option('maintenance_mode_heading', $maintenance_mode_heading);
  }

  if (isset($_POST['maintenance_mode_message'])) {
    $maintenance_mode_message = sanitize_textarea_field($_POST['maintenance_mode_message']);
    update_option('maintenance_mode_message', $maintenance_mode_message);
  }

  // Save secret phrase when the form is submitted
  if (isset($_POST['maintenance_mode_secret_phrase'])) {
    $secret_phrase = sanitize_text_field($_POST['maintenance_mode_secret_phrase']);
    update_option('maintenance_mode_secret_phrase', $secret_phrase);
  }

  // Get the current settings values
  $maintenance_mode = get_option('maintenance_mode', false);
  $maintenance_mode_heading = get_option('maintenance_mode_heading', 'Maintenance Mode');
  $maintenance_mode_message = get_option('maintenance_mode_message', 'This website is currently undergoing maintenance. Please check back soon.');
  $secret_phrase = get_secret_phrase();

  // Determine the current status
  $current_status = $maintenance_mode ? 'Activated' : 'Deactivated';

  // Determine if the secret phrase field should be shown
  $show_secret_phrase_field = isset($_POST['show_advanced_options']) ? ($_POST['show_advanced_options'] === 'true') : false;
  $advanced_options_link_text = $show_secret_phrase_field ? 'Hide Advanced Options' : 'Show Advanced Options';

  // Escape outputted variables
  $current_status = esc_html($maintenance_mode ? 'Activated' : 'Deactivated');
  $advanced_options_link_text = esc_html($show_secret_phrase_field ? 'Hide Advanced Options' : 'Show Advanced Options');

  // ADMIN SETTINGS PAGE
  ?>
  <div class="wrap">
    <h1>Minimal Maintenance Mode</h1>
    <?php if (isset($message)) { ?>
      <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html($message); ?></p>
      </div>
    <?php } ?>
    <p><b>Current status: <?php echo esc_html($current_status); ?></b></p>
    <form method="post" action="">
      <label for="maintenance_mode_heading">
        Custom heading:
        <br>
        <input type="text" id="maintenance_mode_heading" name="maintenance_mode_heading" value="<?php echo esc_attr($maintenance_mode_heading); ?>">
      </label>
      <p></p>
      <label for="maintenance_mode_message">
        Custom message:
        <br>
        <textarea id="maintenance_mode_message" name="maintenance_mode_message" rows="5" cols="50"><?php echo esc_textarea($maintenance_mode_message); ?></textarea>
      </label>
      <p></p>
      <p>
        <a href="#" id="advanced-options-link"><?php echo esc_html($advanced_options_link_text); ?></a>
      </p>
      <div id="advanced-options-fields"<?php if (!$show_secret_phrase_field) { ?> style="display: none;"<?php } ?>>
        <label for="maintenance_mode_secret_phrase">
          Secret phrase:
          <br>
          <input type="text" id="maintenance_mode_secret_phrase" name="maintenance_mode_secret_phrase" value="<?php echo esc_attr($secret_phrase); ?>">
          <br>
          <span class="description"><i>Optional</i>. Show your site to people without an account. <br>Place '?[SECRET PHRASE]' after any URL to bypass the maintenance mode for a week. Leave blank to disable the feature.</span>
        </label>
      </div>
      <p></p>
      <input type="hidden" name="show_advanced_options" id="show_advanced_options" value="<?php echo esc_attr($show_secret_phrase_field ? 'true' : 'false'); ?>">
      <p>
        <input type="submit" class="button-primary" name="save_activate" value="Save and activate">
        <input type="submit" class="button-primary" name="save_deactivate" value="Save and deactivate">
      </p>
    </form>
  </div>

  <script>
    document.getElementById('advanced-options-link').addEventListener('click', function(event) {
      event.preventDefault();
      var advancedOptionsFields = document.getElementById('advanced-options-fields');
      var showAdvancedOptionsField = document.getElementById('show_advanced_options');
      if (advancedOptionsFields.style.display === 'none') {
        advancedOptionsFields.style.display = 'block';
        showAdvancedOptionsField.value = 'true';
        event.target.innerHTML = 'Hide Advanced Options';
      } else {
        advancedOptionsFields.style.display = 'none';
        showAdvancedOptionsField.value = 'false';
        event.target.innerHTML = 'Show Advanced Options';
      }
    });
  </script>
  <?php
}

// Hook the maintenance mode settings page into the admin menu
add_action('admin_menu', 'maintenance_mode_settings_page');

// Add a link to the plugin settings on the plugins page
function maintenance_mode_plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=maintenance-mode">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'maintenance_mode_plugin_settings_link');

/*
Minimal Maintenance Mode is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

Minimal Maintenance Mode is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Minimal Maintenance Mode. If not, see https://gnu.org/licenses/gpl-3.0.html.
*/
