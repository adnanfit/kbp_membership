<?php

function mc_enqueue_admin_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    
    // Enqueue Bootstrap JS (optional)
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'mc_enqueue_admin_scripts');

function mc_register_member_data_menu() {
    add_menu_page(
        'Membership Card Settings', 
        'Membership Card Settings', 
        'manage_options',           
        'membership-card-settings', 
        'mc_usage_guide_page',      
        'dashicons-id',             
        6                            
    );

    add_submenu_page(
        'membership-card-settings',   
        'Member Data',                
        'Member Data',                
        'manage_options',             
        'member-data',                
        'mc_member_data_page'         
    );
}
add_action('admin_menu', 'mc_register_member_data_menu');

function mc_usage_guide_page() {
    echo '<div class="wrap">
        <h1>Usage Guide</h1>
        <p>The shortcode <code>[kbp_membership_form]</code> is available for displaying the membership form.</p>
        <p>Steps to implement:</p>
        <ul>
            <li>Step 1: Use <code>[kbp_membership_form]</code> in a page or post.</li>
            <li>Step 2: Fill out and submit the membership form.</li>
            <li>Step 3: View and manage member data in the "Member Data" section.</li>
        </ul>
    </div>';
}

function mc_member_data_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . "membership_data";

    // Check if the export CSV action is triggered
    if (isset($_GET['export_csv'])) {
        mc_export_csv($table_name, $_GET['filter'] ?? '', $_GET['start_date'] ?? '', $_GET['end_date'] ?? '');
        return; // Stop further processing
    }

    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        echo '<div class="wrap"><div class="alert alert-danger">The table does not exist.</div></div>';
        return;
    }

    // Handle filtering
    $where_clause = 'WHERE 1=1';
    if (isset($_POST['filter'])) {
        $filter_value = sanitize_text_field($_POST['filter']);
        $where_clause .= $wpdb->prepare(" AND full_name LIKE %s", '%' . $wpdb->esc_like($filter_value) . '%');
    }

    // Handle date filtering
    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $start_date = sanitize_text_field($_POST['start_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $where_clause .= $wpdb->prepare(" AND created_at BETWEEN %s AND %s", $start_date, $end_date);
    }

    $members = $wpdb->get_results("SELECT * FROM $table_name $where_clause");

    echo '<div class="wrap">
        <h1>Member Data</h1>
        <form method="post" class="form-inline mb-3">
            <input type="text" name="filter" placeholder="Search by name" class="form-control mr-2" />
            <input type="date" name="start_date" class="form-control mr-2" />
            <input type="date" name="end_date" class="form-control mr-2" />
            <input type="submit" value="Filter" class="btn btn-primary" />
        </form>
        <a href="' . admin_url('admin.php?page=member-data&export_csv=true&filter=' . urlencode($_POST['filter'] ?? '') . '&start_date=' . urlencode($_POST['start_date'] ?? '') . '&end_date=' . urlencode($_POST['end_date'] ?? '')) . '" class="btn btn-success mb-3">Export as CSV</a>
        <hr>';

    if (empty($members)) {
        echo '<div class="alert alert-warning">No members found.</div>';
    } else {
        echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>CNIC</th>
                    <th>Address</th>
                    <th>Birth Year</th>
                    <th>District</th>
                    <th>Gender</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($members as $member) {
            echo '<tr>
                <td>' . esc_html($member->id) . '</td>
                <td>' . esc_html($member->full_name) . '</td>
                <td>' . esc_html($member->phone_number) . '</td>
                <td>' . esc_html($member->cnic) . '</td>
                <td>' . esc_html($member->address) . '</td>
                <td>' . esc_html($member->birth_year) . '</td>
                <td>' . esc_html($member->district) . '</td>
                <td>' . esc_html($member->gender) . '</td>';

            if (!empty($member->image_url)) {
                echo '<td><a href="' . esc_url($member->image_url) . '" target="_blank">
                        <img src="' . esc_url($member->image_url) . '" width="50" height="50" style="object-fit:cover;" />
                    </a></td>';
            } else {
                echo '<td>No Image</td>';
            }

            echo '</tr>';
        }

        echo '</tbody>
        </table>';
    }

    echo '</div>';
}

function mc_export_csv($table_name, $filter = '', $start_date = null, $end_date = null) {
    global $wpdb;

    // Initialize the WHERE clause
    $where_clause = 'WHERE 1=1';

    // Filter by name if provided
    if (!empty($filter)) {
        $where_clause .= $wpdb->prepare(" AND full_name LIKE %s", '%' . $wpdb->esc_like($filter) . '%');
    }

    // Filter by date if provided
    if (!empty($start_date) && !empty($end_date)) {
        $where_clause .= $wpdb->prepare(" AND created_at BETWEEN %s AND %s", $start_date, $end_date);
    }

    // Fetch the members
    $members = $wpdb->get_results("SELECT * FROM $table_name $where_clause");

    // Set headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="member_data.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output the CSV
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Full Name', 'Phone Number', 'CNIC', 'Address', 'Birth Year', 'District', 'Gender', 'Image URL']);

    foreach ($members as $member) {
        fputcsv($output, [
            $member->id,
            $member->full_name,
            $member->phone_number,
            $member->cnic,
            $member->address,
            $member->birth_year,
            $member->district,
            $member->gender,
            $member->image_url,
        ]);
    }

    fclose($output);
    exit();
}

?>