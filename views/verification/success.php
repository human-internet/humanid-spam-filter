<?php

namespace humanid_spam_filter;

$client_secret = get_option( 'hidsf_client_secret', '' );
$client_id     = get_option( 'hidsf_client_id', '' );
$random_key    = VerificationModule::generateRandomKey();
if ( isset( $_GET['et'] ) ) {
	esc_html_e( "Please wait...", 'humanid-spam-filter' );

	$et   = sanitize_text_field( $_GET['et'] );
	$et   = strip_tags( (string) wp_unslash( $et ) );
	$resp = wp_remote_post( 'https://core.human-id.org/v0.0.3/server/users/exchange', [
		'headers' => [
			"Content-Type"  => 'application/json',
			"client-id"     => $client_id,
			"client-secret" => $client_secret,
		],
		'body'    => wp_json_encode( [ "exchangeToken" => $et ] )
	] );
	if ( gettype( $resp ) == 'object' ) : ?>
        <script>
            window.opener.verificationFailed("<?php esc_html_e( "An error occurred. Please try again", 'humanid-spam-filter' )?>")
            window.close();
        </script>

	<?php else:
		$body = json_decode( $resp['body'] );

		if ( $body->success ):
			$human_id = $body->data->appUserId;
			update_option( $random_key, $human_id ); ?>
            <script>
                window.opener.verificationSuccess("<?php echo esc_attr( $random_key )?>")
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