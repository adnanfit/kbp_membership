<?php
// Handle AJAX request
function handle_membership_form() {
    global $wpdb;
    $table_name = $wpdb->prefix . "membership_data";

    // Sanitize and assign form data
    $full_name = sanitize_text_field($_POST['full_name']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $address = sanitize_textarea_field($_POST['address']);
    $birth_year = intval($_POST['birth_year']);
    $district = sanitize_text_field($_POST['district']);
    $tehsil = sanitize_text_field($_POST['tehsil']); // New field added

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploaded_file = $_FILES['image'];
        $upload_overrides = ['test_form' => false];
        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $image_url = $movefile['url'];
        } else {
            $error_message = 'Image upload error: ' . esc_html($movefile['error']);
            wp_send_json_error($error_message);
            return;
        }
    }

    // Insert data into the database
    $inserted = $wpdb->insert($table_name, [
        'full_name' => $full_name,
        'phone_number' => $phone_number,
        'address' => $address,
        'birth_year' => $birth_year,
        'district' => $district,
        'tehsil' => $tehsil, // Inserting new field
        'image_url' => $image_url,
    ]);

    if ($inserted === false) {
        $error_message = 'Database error: ' . esc_html($wpdb->last_error);
        wp_send_json_error($error_message);
    } else {
        // Redirect after successful form submission
        $submission_id = $wpdb->insert_id; // Get the last inserted ID

        // Check if the "get-card" page exists
        $page_title = 'Get Card';
        $page_content = '[membership_card]'; // Replace with your actual shortcode
        $page_slug = 'get-card';

        // Check if the page exists
        $existing_page = get_page_by_path($page_slug);
        if (!$existing_page) {
            // Create the page if it does not exist
            $new_page = [
                'post_title'   => $page_title,
                'post_content' => $page_content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $page_slug,
            ];
            wp_insert_post($new_page);
        }

        // Redirect to the card page
        wp_send_json_success(['message' => 'Form submitted successfully!', 'redirect' => home_url('/get-card?submission_id=' . $submission_id)]);
    }
}

add_action('wp_ajax_handle_membership_form', 'handle_membership_form');
add_action('wp_ajax_nopriv_handle_membership_form', 'handle_membership_form');
