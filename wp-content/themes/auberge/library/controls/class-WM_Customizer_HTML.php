<?php
/**
 * Customizer custom controls
 *
 * Customizer custom HTML.
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */





class WM_Customizer_HTML extends WP_Customize_Control {

	public $type = 'html';

	public $content = '';

	public function render_content() {
		if ( isset( $this->label ) && ! empty( $this->label ) ) {
			echo '<span class="customize-control-title">' . $this->label . '</span>';
		}

		if ( isset( $this->content ) ) {
			echo $this->content;
		} else {
			esc_html_e( 'Please set the `content` parameter for the HTML control.', 'glamour' );
		}

		if ( isset( $this->description ) && ! empty( $this->description ) ) {
			echo '<span class="description customize-control-description">' . $this->description . '</span>';
		}
	}

} // /WM_Customizer_HTML
