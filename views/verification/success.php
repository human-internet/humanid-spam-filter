<?php

namespace humanid_spam_filter;
if ( ! defined( 'ABSPATH' ) ) exit;

$hidsf_client_secret = get_option( 'hidsf_client_secret', '' );
$hidsf_client_id     = get_option( 'hidsf_client_id', '' );
$hidsf_random_key    = VerificationModule::generateRandomKey();
if ( isset( $_GET['et'] ) ) {
	esc_html_e( "Please wait...", 'humanid-spam-filter' );

	$hidsf_et   = sanitize_text_field( (string) wp_unslash($_GET['et'] ));
	$hidsf_et   = wp_strip_all_tags( $hidsf_et );
	$hidsf_resp = wp_remote_post( 'https://core.human-id.org/v0.0.3/server/users/exchange', [
		'headers' => [
			"Content-Type"  => 'application/json',
			"client-id"     => $hidsf_client_id,
			"client-secret" => $hidsf_client_secret,
		],
		'body'    => wp_json_encode( [ "exchangeToken" => $hidsf_et ] )
	] );
	if ( gettype( $hidsf_resp ) == 'object' ) : ?>
        <script>
            window.opener.verificationFailed("<?php esc_html_e( "An error occurred. Please try again", 'humanid-spam-filter' )?>")
            window.close();
        </script>

	<?php else:
		$body = json_decode( $hidsf_resp['body'] );

		if ( $body->success ):
			$human_id = $body->data->appUserId;
			update_option( $hidsf_random_key, $human_id ); ?>
            <script>
                window.opener.verificationSuccess("<?php echo esc_attr( $hidsf_random_key )?>")
                window.close();
            </script>
		<?php else: ?>
            <script>
                window.opener.verificationFailed(" <?php echo esc_attr( $body->message->data )?>")
                window.close();
            </script>
		<?php endif;

	endif;
} else {
	esc_html_e( "Invalid Request", 'humanid-spam-filter' );
	?>
    <script>
        window.close();
    </script>
	<?php
}

?>