<?php

namespace humanid_spam_filter;

if ( isset( $_GET['message'] ) ) {
	$message = sanitize_text_field( $_GET['message'] );
	$message = strip_tags( (string) wp_unslash( $message ) );
	?>
    <script>
        window.opener.verificationFailed(" <?php echo esc_html($message)?>")
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