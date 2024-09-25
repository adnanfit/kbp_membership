<?php
function enqueue_js_pdf() {
    // Enqueue jsPDF and html2canvas
    wp_enqueue_script('jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', [], null, true);
    wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', [], null, true);
    // Google Poppins Font
    wp_enqueue_style('google-fonts-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap', false);
}
add_action('wp_enqueue_scripts', 'enqueue_js_pdf');

function display_membership_card() {
    ob_start();

    // Get the submission ID from the URL
    $submission_id = isset($_GET['submission_id']) ? intval($_GET['submission_id']) : 0;

    global $wpdb;
    $table_name = $wpdb->prefix . "membership_data";

    // Fetch member data based on submission ID
    $member = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $submission_id));

    if (!$member) {
        return '<div class="wrap"><h1>No Member Found</h1></div>';
    }
    ?>
<div class="wrap">
    <h1>Your Membership Card</h1>
    <div id="membershipCard" class="membership-card">
        <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../assets/img/cardbg.jpg'); ?>" alt="Background"
            class="card-bg" />
        <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../assets/img/Kb-logo.png'); ?>" alt="kbp_logo"
            class="kbp_logo" />
        <img src="<?php echo esc_url($member->image_url); ?>" alt="Profile Image" class="profile-img" />
        <div class="card-content">
            <h2><?php echo esc_html($member->full_name); ?></h2>
            <p><strong>District:</strong> <?php echo esc_html($member->district); ?></p>
            <p><strong>Tehsil:</strong> <?php echo esc_html($member->tehsil); ?></p> <!-- Added Tehsil Field -->
            <p><strong>Member ID:</strong> <?php echo esc_html($member->id); ?></p>
        </div>
    </div>
    <button id="downloadPdf" class="button button-primary">Download PDF</button>
</div>

<style>
.wrap {
    font-family: 'Poppins', sans-serif;
}

.membership-card {
    position: relative;
    width: 400px;
    /* Adjust width as needed */
    height: 600px;
    /* Adjust height as needed */
    border: none;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: none;
    background-color: #fff;
}

.card-bg,
.kbp_logo,
.profile-img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.profile-img {
    position: absolute;
    top: 60%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 130px !important;
    height: 130px !important;
    border-radius: 50% !important;
    border: 3px solid #fff;
    box-shadow: none;
}

.kbp_logo {
    position: absolute;
    top: 32%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: auto;
}

.card-content {
    position: absolute;
    bottom: 5%;
    left: 50%;
    transform: translate(-50%, 0);
    color: #000;
    text-align: center;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 5px 10px;
    border-radius: 5px;
    width: 300px;
}

.card-content h2 {
    margin: 0;
    font-size: 20px;
    font-weight: bold;
}

.card-content p {
    margin: 5px 0;
    font-size: 16px;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('downloadPdf').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;

        // Set PDF size to 53.98 mm width and 85.60 mm height
        const pdf = new jsPDF('p', 'mm', [53.98, 85.60]);

        // Use html2canvas to capture the membership card
        html2canvas(document.querySelector("#membershipCard"), {
            scale: 2, // Increase the scale to improve resolution
            useCORS: true, // Enable CORS to load images from different domains
            backgroundColor: null // Set background to transparent
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg');

            // Add the canvas image to the PDF with the correct dimensions
            pdf.addImage(imgData, 'JPEG', 0, 0, 53.98,
                85.60); // Use specified dimensions for the card

            const filename = 'KPB-<?php echo sanitize_title($member->full_name); ?>-' + '.pdf';
            pdf.save(filename);
        }).catch(function(error) {
            console.error('Error capturing the canvas:', error);
        });
    });
});
</script>
<?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('membership_card', 'display_membership_card');