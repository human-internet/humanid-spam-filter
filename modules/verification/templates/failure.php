<?php

namespace humanid_spam_filter;

if ( isset( $_GET['message'] ) ) {
	$message = sanitize_text_field( $_GET['message'] );
	$message = strip_tags( (string) wp_unslash( $message ) );
	?>
    <script>
        window.opener.verificationFailed(" <?php echo $message?>")
        window.close();
    </script>


	<?php
} else {
	_e( "Invalid Request", HIDSF_TEXT_DOMAIN );
	?>
    <script>
        window.opener.verificationFailed("")
        window.close();
    </script>
	<?php
}

?>