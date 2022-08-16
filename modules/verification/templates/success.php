<?php

namespace humanid_spam_filter;

$client_secret = get_option( 'hidsf_client_secret', '' );
$client_id     = get_option( 'hidsf_client_id', '' );
$random_key    = VerificationModule::generateRandomKey();
if ( isset( $_GET['et'] ) ) {
	_e( "Please wait...", HIDSF_TEXT_DOMAIN );

	$et   = $_GET['et'];
	$et   = strip_tags( (string) wp_unslash( $et ) );
	$resp = wp_remote_post( 'https://core.human-id.org/v0.0.3/server/users/exchange', [
		'headers' => [
			"Content-Type"  => 'application/json',
			"client-id"     => $client_id,
			"client-secret" => $client_secret,
		],
		'body'    => json_encode( [ "exchangeToken" => $et ] )
	] );
	if ( gettype( $resp ) == 'object' ) : ?>
        <script>
            window.opener.verificationFailed("<?php _e( "An error occurred. Please try again", HIDSF_TEXT_DOMAIN )?>")
            window.close();
        </script>

	<?php else:
		$body = json_decode( $resp['body'] );

		if ( $body->success ):
			$human_id = $body->data->appUserId;
			update_option( $random_key, $human_id ); ?>
            <script>
                window.opener.verificationSuccess("<?php echo $random_key?>")
                window.close();
            </script>
		<?php else: ?>
            <script>
                window.opener.verificationFailed(" <?php echo $body->message->data?>")
                window.close();
            </script>
		<?php endif;

	endif;
} else {
	_e( "Invalid Request", HIDSF_TEXT_DOMAIN );
	?>
    <script>
        window.close();
    </script>
	<?php
}

?>