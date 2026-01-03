<?php

namespace humanid_spam_filter;
if ( ! defined( 'ABSPATH' ) ) exit;

if ( isset( $_GET['message'] ) ) {
	$hidsf_message = sanitize_text_field( (string) wp_unslash( $_GET['message']) );
	$hidsf_message = wp_strip_all_tags( $hidsf_message );
	?>
    <script>
        window.opener.verificationFailed(" <?php echo esc_html($hidsf_message)?>")
        window.close();
    </script>


	<?php
} else {
	esc_html_e( "Invalid Request", 'humanid-spam-filter' );
	?>
    <script>
        window.opener.verificationFailed("")
        window.close();
    </script>
	<?php
}

?>