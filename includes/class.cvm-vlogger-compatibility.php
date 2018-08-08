<?php

class CVM_Vlogger_Actions_Compatibility {
	/**
	 * Theme name
	 * @var string
	 */
	private $theme_name;

	/**
	 * CVM_Vlogger_Actions_Compatibility constructor.
	 *
	 * @param string $theme_name
	 */
	public function __construct( $theme_name ) {
		$this->theme_name = $theme_name;
		add_filter( 'cvm_theme_support', array( $this, 'theme_support' ) );
		add_filter( 'cvm_video_post_content', array( $this, 'add_url_to_content' ), 10, 3 );
		add_action( 'cvm_post_insert', array( $this, 'post_inserted' ), 10, 4 );
	}

	/**
	 * @param array $themes
	 *
	 * @return array
	 */
	public function theme_support( $themes ) {
		$theme_name = strtolower( $this->theme_name );
		$themes[ $theme_name ] = array(
			'post_type'    => 'post',
			'taxonomy'     => false,
			'tag_taxonomy' => 'post_tag',
			'post_meta'    => array(),
			'post_format'  => 'video',
			'theme_name'   => $this->theme_name,
			'url'          => 'https://themeforest.net/item/vlogger-professional-video-tutorials-wordpress-theme/20414115?ref=cboiangiu',
			'extra_meta' 	=> array(
				'vlogger_video_duration' => array(
					'type' 	=> 'video_data',
					'value' => 'human_duration'
				)
			),
		);

		return $themes;
	}

	/**
	 * @param $post_content
	 * @param $video
	 * @param $theme_import
	 *
	 * @return string
	 */
	public function add_url_to_content( $post_content, $video, $theme_import ) {
		if ( ! $theme_import ) {
			return $post_content;
		}

		$url = cvm_video_url( $video['video_id'] );

		return $url . "\n\n" . $post_content;
	}

	/**
	 * @param $post_id
	 * @param $video
	 * @param $theme_import
	 * @param $post_type
	 */
	public function post_inserted( $post_id, $video, $theme_import, $post_type ){
		if( !$theme_import ){
			return;
		}
		// theme detects oembed field in post meta. Add the iframe to this field.
		update_post_meta( $post_id, '_oembed_' . md5( time() ), cvm_video_embed( $video['video_id'] ) );
	}
}