	<?php
	$video_type  =  Slzexploore_Core::get_params( 'video-type');
	$class_cate = $this->get_field( $post_meta, 'video_type' );
	$class_upload ="";
	$class_vimeo_id = "";
	$class_youtube_id = "";
	if ( $class_cate == 'video-upload' ){
		$class_upload = "active";
	}else if( $class_cate == 'vimeo' ) {
		$class_vimeo_id = "active";
	}
	else if( $class_cate == 'youtube' ) {
		$class_youtube_id = "active";
	}
	?>
	<div class="slz-custom-meta container-video-option" >
		<div class="slz-video-meta" >
			<div class="slz-meta-row active" >
				<div class="general-desc">
					<span><?php esc_html_e( 'Please, choose "Video Format" to use "Featured Video". It will be embedded in the post and Featured Image will use to thumb of this post.', 'slzexploore-core' );?></span>
				</div>
			</div>
			<div class="slz-meta-row active" >
				<div class="slz-desc">
					<span><?php esc_html_e( 'Video Thumbnail', 'slzexploore-core' );?></span>
				</div>
				<div class="slz-field">
					<?php echo ( $this->check_box( 'slzexploore_feature_video[generate_thumnail]',
															$this->get_field( $post_meta, 'generate_thumnail'),
														array( 'class'=>'') ) );
							esc_html_e( 'Create thumbnail from video and using it as featured image? (Only for youtube and vimeo).', 'slzexploore-core' );?>
				</div>
			</div>
			<div class="slz-meta-row active" >
				<div class="slz-desc">
					<span><?php esc_html_e( 'Type', 'slzexploore-core' );?></span>
				</div>
				<div class="slz-field">
					<?php echo ( $this->drop_down_list('slzexploore_feature_video[video_type]',
															$this->get_field( $post_meta, 'video_type' ),
															$video_type,
															array('id'=>'slzexploore_core_mbox_video_type') ) );?>
				</div>
			</div>
			<div class="slz-meta-row video_upload <?php echo esc_attr( $class_upload ); ?>" >
				<?php
					$this->upload_video( "slzexploore_feature_video[upload_video]",$this->get_field($post_meta,'upload_video'), esc_html__('MP4 Upload', 'slzexploore-core' ), esc_html__('Choose file .mp4 to upload', 'slzexploore-core' ));?>
			</div>
			<div class="slz-meta-row vimeo-id <?php echo esc_attr( $class_vimeo_id ); ?> " >
				<div class="slz-desc">
					<label><?php esc_html_e( 'Vimeo ID', 'slzexploore-core' );?></label>
				</div>
				<div class="slz-field">
					<?php echo ( $this->text_field( 'slzexploore_feature_video[vimeo_id]',
															$this->get_field( $post_meta, 'vimeo_id' ),
															array() ) );?>
					<p class="description"><?php echo esc_html__('Example the Video ID for ', 'slzexploore-core') . 'http://vimeo.com/86323053 is 86323053';?></p>
				</div>
			</div>
			<div class="slz-meta-row youtube-id <?php echo esc_attr( $class_youtube_id ); ?> " >
				<div class="slz-desc">
					<label><?php esc_html_e( 'Youtube ID', 'slzexploore-core' );?></label>
				</div>
				<div class="slz-field">
					<?php echo ( $this->text_field( 'slzexploore_feature_video[youtube_id]',
															$this->get_field( $post_meta, 'youtube_id' ),
															array() ) );?>
					<p class="description"><?php echo esc_html__('Example the Video ID for ', 'slzexploore-core') . 'http://www.youtube.com/v/8OBfr46Y0cQ is 8OBfr46Y0cQ';?></p>
				</div>
			</div>
		</div>
	</div>