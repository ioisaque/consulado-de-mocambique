<?php
/**
 * Options Importer class.
 * 
 * @since 1.0
 */

class Slzexploore_Core_Options_Importer {

	/**
	 * Stores the singleton instance.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * The attachment ID.
	 *
	 * @access private
	 *
	 * @var int
	 */
	private $file_id;

	/**
	 * The transient key template used to store the options after upload.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $transient_key = 'options-import-%d';

	/**
	 * The plugin version.
	 */
	const VERSION = 5;

	/**
	 * Stores the import data from the uploaded file.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $import_data;


	public function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public function __clone() { wp_die( "Please don't __clone Slzexploore_Core_Options_Importer" ); }

	public function __wakeup() { wp_die( "Please don't __wakeup Slzexploore_Core_Options_Importer" ); }

	public function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Slzexploore_Core_Options_Importer;
			self::$instance->setup();
		}
		return self::$instance;
	}


	/**
	 * Initialize the singleton.
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'export_filters', array( $this, 'export_filters' ) );
		add_filter( 'export_args', array( $this, 'export_args' ) );
		add_action( 'export_wp', array( $this, 'export_wp' ) );
		add_action( 'admin_init', array( $this, 'register_importer' ) );
	}


	/**
	 * Register our importer.
	 *
	 * @return void
	 */
	public function register_importer() {
		if ( function_exists( 'register_importer' ) ) {
			register_importer( 'options-import', esc_html__( 'Taxonomy Options', 'slzexploore-core' ), esc_html__( 'Import taxonomy options from a JSON file', 'slzexploore-core' ), array( $this, 'dispatch' ) );
		}
	}


	/**
	 * Add a radio option to export options.
	 *
	 * @return void
	 */
	public function export_filters() {
		?>
		<p class="description"><?php esc_html_e( 'Below option is used to export taxonomy options. This is not include in All content option when export.', 'slzexploore-core' ); ?></p>
		<p><label><input type="radio" name="content" value="options" /> <?php esc_html_e( 'Taxonomy Options', 'slzexploore-core' ); ?></label></p>
		<?php
	}


	/**
	 * If the user selected that they want to export options, indicate that in the args and
	 * discard anything else. This will get picked up by Slzexploore_Core_Options_Importer::export_wp().
	 *
	 * @param  array $args The export args being filtered.
	 * @return array The (possibly modified) export args.
	 */
	public function export_args( $args ) {
		if ( ! empty( $_GET['content'] ) && 'options' == $_GET['content'] ) {
			return array( 'options' => true );
		}
		return $args;
	}

	/**
	 *  Create data to export.
	 *
	 * @return array name of options to export
	 */
	public function export_data() {
		$option_names = array();
		$custom_taxonomies = Slzexploore_Core::get_config( 'custom_taxonomies' );
		if( !empty($custom_taxonomies) ){
			foreach($custom_taxonomies as $term){
				$term_ids = get_terms( $term , array('fields'=> 'ids') );
				if( !empty($term_ids) ) {
					foreach($term_ids as $id){
						$option_names[] = SLZEXPLOORE_CORE_TAXONOMY_CUS . $id;
					}
				}
			}
		}
		return $option_names;
	}

	/**
	 * Export options as a JSON file if that's what the user wants to do.
	 *
	 * @param  array $args The export arguments.
	 * @return void
	 */
	public function export_wp( $args ) {
		if ( ! empty( $args['options'] ) ) {
			global $wpdb;

			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty( $sitename ) ) {
				$sitename .= '.';
			}
			$filename = $sitename . 'wp_options.' . date( 'Y-m-d' ) . '.json';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );


			$option_names = $this->export_data();
			if ( ! empty( $option_names ) ) {
				$export_options = array();
				// we're going to use a random hash as our default, to know if something is set or not
				$hash = '048f8580e913efe41ca7d402cc51e848';
				foreach ( $option_names as $option_name ) {
					$option_value = get_option( $option_name, $hash );
					// only export the setting if it's present
					if ( $option_value !== $hash ) {
						$export_options[ $option_name ] = maybe_serialize( $option_value );
					}
				}
				$JSON_PRETTY_PRINT = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
				echo json_encode( array( 'options' => $export_options), $JSON_PRETTY_PRINT );
			}
			exit;
		}
	}


	/**
	 * Registered callback function for the Options Importer
	 *
	 * Manages the three separate stages of the import process.
	 *
	 * @return void
	 */
	public function dispatch() {
		$this->header();

		if ( empty( $_GET['step'] ) ) {
			$_GET['step'] = 0;
		}

		switch ( intval( $_GET['step'] ) ) {
			case 0:
				$this->greet();
				break;
			case 1:
				check_admin_referer( 'import-upload' );
				if ( $this->handle_upload() ) {
					$this->import_data = get_transient( $this->transient_key() );
					if ( false !== $this->import_data ) {
						$this->import();
					}
				} else {
					echo '<p><a href="' . esc_url( admin_url( 'admin.php?import=options-import' ) ) . '">' . esc_html__( 'Return to File Upload', 'slzexploore-core' ) . '</a></p>';
				}
				break;
		}

		$this->footer();
	}


	/**
	 * Start the options import page HTML.
	 *
	 * @return void
	 */
	private function header() {
		echo '<div class="wrap">';
		echo '<h2>' . esc_html__( 'Import WordPress Options', 'slzexploore-core' ) . '</h2>';
	}


	/**
	 * End the options import page HTML.
	 *
	 * @return void
	 */
	private function footer() {
		echo '</div>';
	}


	/**
	 * Display introductory text and file upload form.
	 *
	 * @return void
	 */
	private function greet() {
		echo '<div class="narrow">';
		echo '<p>'.esc_html__( 'Howdy! Upload your WordPress options JSON file and we&#8217;ll import the desired data. You&#8217;ll have a chance to review the data prior to import.', 'slzexploore-core' ).'</p>';
		echo '<p>'.esc_html__( 'Choose a JSON (.json) file to upload, then click Upload file and import.', 'slzexploore-core' ).'</p>';
		wp_import_upload_form( 'admin.php?import=options-import&amp;step=1' );
		echo '</div>';
	}


	/**
	 * Handles the JSON upload and initial parsing of the file to prepare for
	 * displaying author import options
	 *
	 * @return bool False if error uploading or invalid file, true otherwise
	 */
	private function handle_upload() {
		$file = wp_import_handle_upload();

		if ( isset( $file['error'] ) ) {
			return $this->error_message(
				esc_html__( 'Sorry, there has been an error.', 'slzexploore-core' ),
				esc_html( $file['error'] )
			);
		}

		if ( ! isset( $file['file'], $file['id'] ) ) {
			return $this->error_message(
				esc_html__( 'Sorry, there has been an error.', 'slzexploore-core' ),
				esc_html__( 'The file did not upload properly. Please try again.', 'slzexploore-core' )
			);
		}

		$this->file_id = intval( $file['id'] );

		if ( ! file_exists( $file['file'] ) ) {
			wp_import_cleanup( $this->file_id );
			return $this->error_message(
				esc_html__( 'Sorry, there has been an error.', 'slzexploore-core' ),
				sprintf( esc_html__( 'The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', 'slzexploore-core' ), esc_html( $file['file'] ) )
			);
		}

		if ( ! is_file( $file['file'] ) ) {
			wp_import_cleanup( $this->file_id );
			return $this->error_message(
				esc_html__( 'Sorry, there has been an error.', 'slzexploore-core' ),
				esc_html__( 'The path is not a file, please try again.', 'slzexploore-core' )
			);
		}

		$file_contents = file_get_contents( $file['file'] );
		$this->import_data = json_decode( $file_contents, true );
		set_transient( $this->transient_key(), $this->import_data, DAY_IN_SECONDS );
		wp_import_cleanup( $this->file_id );

		return $this->run_data_check();
	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @return void
	 */
	private function import() {
		if ( $this->run_data_check() ) {

			$options_to_import = array();
			$options_to_import = array_keys( $this->import_data['options'] );

			$hash = '048f8580e913efe41ca7d402cc51e848';

			foreach ( (array) $options_to_import as $option_name ) {
				if ( isset( $this->import_data['options'][ $option_name ] ) ) {
					$option_value = maybe_unserialize( $this->import_data['options'][ $option_name ] );
					update_option( $option_name, $option_value );
				}
			}

			$this->clean_up();
			echo '<p>' . esc_html__( 'All done. That was easy.', 'slzexploore-core' ) . ' <a href="' . admin_url() . '">' . esc_html__( 'Have fun!', 'slzexploore-core' ) . '</a>' . '</p>';
		}
	}


	/**
	 * Run a series of checks to ensure we're working with a valid JSON export.
	 *
	 * @return bool true if the file and data appear valid, false otherwise.
	 */
	private function run_data_check() {
		if ( empty( $this->import_data['options'] ) ) {
			$this->clean_up();
			return $this->error_message( esc_html__( 'Sorry, there has been an error. This file appears valid, but does not seem to have any options.', 'slzexploore-core' ) );
		}
		return true;
	}


	private function transient_key() {
		return sprintf( $this->transient_key, $this->file_id );
	}


	private function clean_up() {
		delete_transient( $this->transient_key() );
	}


	/**
	 * A helper method to keep DRY with our error messages. Note that the error messages
	 * must be escaped prior to being passed to this method (this allows us to send HTML).
	 *
	 * @param  string $message The main message to output.
	 * @param  string $details Optional. Additional details.
	 * @return bool false
	 */
	private function error_message( $message, $details = '' ) {
		echo '<div class="error"><p><strong>' . esc_html( $message ) . '</strong>';
		if ( ! empty( $details ) ) {
			echo '<br />' . wp_kses_post( $details );
		}
		echo '</p></div>';
		return false;
	}
}