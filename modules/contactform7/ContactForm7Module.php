<?php

namespace humanid_spam_filter;

use WPCF7_Submission;
use WPCF7_TagGenerator;

class ContactForm7Module extends Module {
	private $error_message = '';

	/**
	 * @since v1.1.0
	 * Adds a contact form 7 tag
	 */
	public function addTag() {

		// Test if new 4.6+ functions exists
		if ( function_exists( 'wpcf7_add_form_tag' ) ) {
			wpcf7_add_form_tag(
				'humanid',
				[ $this, 'tagHandler' ],
				array(
					'name-attr'    => true,
					'do-not-store' => true,
					'not-for-mail' => true
				)
			);
		} else {
			wpcf7_add_shortcode( 'humanid', [ $this, 'tagHandler' ], true );
		}
	}

	/**
	 * @since v1.1.0
	 * Handler for addTag()
	 */
	public function tagHandler( $tag ) {
		$input = "<input type='hidden' name='{$tag->name}' class='human-id'/>";

		return $input;
	}

	/**
	 * @since v1.1.0
	 * Handler for addTag()
	 */
	public function tagGenerator() {
		if ( class_exists( 'WPCF7_TagGenerator' ) ) {
			$tag_generator = WPCF7_TagGenerator::get_instance();
			$tag_generator->add( 'humanid', 'humanID', [ $this, 'tgPaneCallback' ] );
		} else if ( function_exists( 'wpcf7_add_tag_generator' ) ) {
			wpcf7_add_tag_generator( 'humanid', 'humanID', 'wpcf7-tg-pane-humanid', [ $this, 'tgPaneCallback' ] );
		}
	}

	public function tgPaneCallback( $contact_form, $args = '' ) {
		if ( class_exists( 'WPCF7_TagGenerator' ) ) :
			$description = __( "Add humanID verification to this form see %s.", HIDSF_TEXT_DOMAIN );
			$desc_link = '<a href="https://wordpress.org/plugins/humanid-spam-filter/" target="_blank"> humanID </a>';
			?>
            <div class="control-box">
                <fieldset>
                    <legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7-honeypot' ) ); ?></label>
                            </th>
                            <td>
                                <input type="text" name="name" class="tg-name oneline"
                                       id="<?php echo esc_attr( $args['content'] . '-name' ); ?>"/>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </fieldset>
            </div>

            <div class="insert-box">
                <input type="text" name="humanid" class="tag code" readonly="readonly" onfocus="this.select()"/>

                <div class="submitbox">
                    <input type="button" class="button button-primary insert-tag"
                           value="<?php echo esc_attr( __( 'Insert Tag', HIDSF_TEXT_DOMAIN ) ); ?>"/>
                </div>

                <br class="clear"/>
            </div>
		<?php endif;
	}

	/**
	 * Validates a contact form, blocks submission if the human id is blocked
	 * @since v1.1.0
	 */
	public function validateForm( $result, $tag ) {
		$name = $tag->name;

		if ( ! isset( $_POST[ $name ] ) ) {
			$this->error_message = __( 'humanID: Please verify that you are not a bot.', HIDSF_TEXT_DOMAIN );
			$result->invalidate( $tag, __( 'Please verify that you are not a bot.', HIDSF_TEXT_DOMAIN ) );
		}

		$human_id_key = wp_filter_nohtml_kses( $_POST[ $name ] );
		$human_id     = get_option( $human_id_key, '' );
		if ( trim( $human_id ) == '' ) {
			$this->error_message = __( ' humanID: Please verify that you are not a bot.', HIDSF_TEXT_DOMAIN );
			$result->invalidate( $tag, __( ' Please verify that you are not a bot.', HIDSF_TEXT_DOMAIN ) );
		}

		$user = User::where( 'human_id', '=', $human_id )->get();
		if ( sizeof( $user ) > 0 ) {
			$user = $user[0];
			if ( $user->blocked == 1 ) {
				$this->error_message = __( 'humanID: This website has blocked you from submitting this form.', HIDSF_TEXT_DOMAIN );
				$result->invalidate( $tag, __( 'This website has blocked you from submitting this form.', HIDSF_TEXT_DOMAIN ) );
			}
		}

		return $result;
	}

	/**
	 * Shows custom error message if human id validation fails
	 * @since v1.1.0
	 */
	public function modifyDisplayMessage( $message, $status ) {
		$submission     = WPCF7_Submission::get_instance();
		$invalid_fields = $submission->get_invalid_fields();

		// show custom message only if the humanID field is the only invalid field
		if ( strlen( trim( $this->error_message ) ) > 0 && sizeof( $invalid_fields ) == 1 ) {
			$message = $this->error_message;
		}

		return $message;
	}

	protected function addActions() {
		parent::addActions();
		add_action( 'wpcf7_init', [ $this, 'addTag' ], 10 );
		add_action( 'wpcf7_admin_init', [ $this, 'tagGenerator' ], 35 );
	}

	protected function addFilters() {
		parent::addFilters();
		add_filter( 'wpcf7_validate_humanid', [ $this, 'validateForm' ], 10, 2 );
		add_filter( 'wpcf7_display_message', [ $this, 'modifyDisplayMessage' ], 999, 2 );

//		add_filter( 'wpcf7_validate', [ $this, 'validateForm' ], 10, 2 );
	}
	/**
	 * $result = apply_filters( "wpcf7_validate_{$type}", $result, $tag );
	 * }
	 *
	 * $result = apply_filters( 'wpcf7_validate', $result, $tags );
	 */
}