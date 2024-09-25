<?php
function mc_membership_form() {
    ob_start();
function enqueue_bootstrap() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3', 'all');
    
    // Enqueue Bootstrap JS (with jQuery as a dependency if needed)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js', array('jquery'), '5.3.3', true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');

    // Helper function to get districts
    function get_pakistan_districts() {
        return array(
            'Abbottabad', 'Astore', 'Attock', 'Awaran', 'Badin', 'Bagh', 'Bahawalnagar',
            'Bahawalpur', 'Bajaur', 'Bannu', 'Barkhan', 'Batagram', 'Bhakkar', 'Bhimber',
            'Buner', 'Chagai', 'Chakwal', 'Charsadda', 'Chiniot', 'Dadu', 'Darel',
            'Dera Bugti', 'Dera Ghazi Khan', 'Dera Ismail Khan', 'Diamer', 'Duki', 'Faisalabad',
            'Ghanche', 'Ghizer', 'Ghotki', 'Gilgit', 'Gujranwala', 'Gujrat', 'Gupis Yasin',
            'Gwadar', 'Hafizabad', 'Hangu', 'Haripur', 'Harnai', 'Hattian', 'Haveli',
            'Hunza', 'Hyderabad', 'Islamabad', 'Jacobabad', 'Jafarabad', 'Jamshoro',
            'Jhal Magsi', 'Jhang', 'Jhelum', 'Kachhi', 'Kalat', 'Karachi Central', 'Karachi East',
            'Karachi South', 'Karachi West', 'Karak', 'Kashmore', 'Kasur', 'Kech',
            'Khairpur', 'Khanewal', 'Kharan', 'Kharmang', 'Khushab', 'Khuzdar', 'Khyber',
            'Killa Abdullah', 'Kohat', 'Kohlu', 'Kolai Pallas', 'Korangi', 'Kotli',
            'Kurram', 'Lahore', 'Lakki Marwat', 'Larkana', 'Lasbela', 'Layyah', 'Lodhran',
            'Loralai', 'Lower Chitral', 'Lower Dir', 'Lower Kohistan', 'Malakand', 'Malir',
            'Mandi Bahauddin', 'Mansehra', 'Mardan', 'Mastung', 'Matiari', 'Mianwali',
            'Mirpur Khas', 'Mirpur', 'Mohmand', 'Multan', 'Musakhel', 'Muzaffarabad',
            'Muzaffargarh', 'Nagar', 'Nankana Sahib', 'Narowal', 'Naseerabad', 'Naushahro Firoze',
            'Neelum', 'North Waziristan', 'Nowshera', 'Nushki', 'Okara', 'Orakzai',
            'Pakpattan', 'Panjgur', 'Peshawar', 'Pishin', 'Poonch', 'Qambar Shahdadkot',
            'Qilla Saifullah', 'Quetta', 'Rahim Yar Khan', 'Rajanpur', 'Rawalpindi', 'Roundu',
            'Sahiwal', 'Sanghar', 'Sargodha', 'Shaheed Benazirabad', 'Shaheed Sikandarabad',
            'Shikarpur', 'Shangla', 'Sherani', 'Sialkot', 'Sujawal', 'Sukkur',
            'South Waziristan', 'Swabi', 'Swat', 'Tando Allahyar', 'Tando Muhammad Khan',
            'Tank', 'Tharparkar', 'Thatta', 'Toba Tek Singh', 'Torghar', 'Umerkot',
            'Upper Dir', 'Upper Kohistan', 'Vehari', 'Washuk', 'Zhob', 'Ziarat'
        );
    }

    // Display the form
    ?>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600&display=swap"
    rel="stylesheet">
<style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
    /* padding: 20px; */
}

#membership-form {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

h2 {
    font-family: 'Montserrat', sans-serif;
    color: #333;
    margin-bottom: 20px;
}
</style>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600&display=swap"
    rel="stylesheet">
<style>
/* General Container Styling */
.custom-form-container {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
}

/* Form Container Styling */
.custom-membership-form {
    background-color: #F9F9F9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-top: 20px;
}

/* Heading Styling */
.custom-membership-form h2 {
    font-family: 'Montserrat', sans-serif;
    color: #333;
    margin-bottom: 20px;
}

/* Label Styling */
.custom-membership-form label,
.form-upload-label {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    margin-bottom: 5px;
    color: #555;
}

/* Form Control Styling (Text Inputs, Textareas, etc.) */
.custom-membership-form .form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
    transition: border-color 0.3s;
}

/* Form Control Focus State */
.custom-membership-form .form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Form Container Width */
.custom-form-container .container {
    max-width: 800px;
    margin: auto;
}

/* Error Message Styling */
.custom-membership-form .text-danger {
    color: red;
}

/* Submit Button Styling */
#btn-submit {
    background: #355E2D;
    color: white;
    border-radius: 10px;
    border: none !important;
    padding: 15px 50px;
    margin-top: 20px;
    transition: background-color 0.3s;
}

/* Submit Button Hover Effect */
#btn-submit:hover {
    background-color: #2a4a22;
}


.form-upload {
    width: 100%;
    border-radius: 5px;
    border: 1px solid #ddd;
    transition: border-color 0.3s;
}

.form-upload:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-upload::-webkit-file-upload-button {
    background-color: #355E2D;
    color: white;
    border: none;
    padding: 8px 15px;
    cursor: pointer;
    transition: background-color 0.3s;
    border-radius: 5px;
}

.form-upload::-webkit-file-upload-button:hover {
    background-color: #0056b3;
}
</style>

<form id="membership-form" class="custom-membership-form" enctype="multipart/form-data">
    <div class="container custom-form-container">
        <h2 class="text-center">Membership Form</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" name="full_name" id="full_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="address">Address:</label>
                <input class="form-control" name="address" id="address" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="birth_year">Date of Birth:</label>
                <input type="date" class="form-control" name="birth_year" id="birth_year" min="1900"
                    max="<?php echo date('Y'); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="district">District:</label>
                <select class="form-control" name="district" id="district" required>
                    <option value="">Select a district</option>
                    <?php foreach (get_pakistan_districts() as $dist) : ?>
                    <option value="<?php echo esc_attr($dist); ?>"><?php echo esc_html($dist); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tehsil">Tehsil:</label>
                <input type="text" class="form-control" name="tehsil" id="tehsil" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="image">Upload Image:</label>
                <input class="form-upload" id="formFileSm" name="image" type="file" required>
            </div>
        </div>
        <div class="text-center">
            <input type="submit" id="btn-submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
    <div id="error-messages" class="text-danger mt-3"></div>
</form>
<?php
    return ob_get_clean();
}
add_shortcode('kbp_membership_form', 'mc_membership_form');