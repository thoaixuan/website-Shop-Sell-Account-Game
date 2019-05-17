<?php 
// Author Profile Feilds
add_action( 'show_user_profile', 'virtue_toolkit_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'virtue_toolkit_show_extra_profile_fields' );
function virtue_toolkit_show_extra_profile_fields( $user ) { 
	if(current_user_can( 'edit_posts') ) { ?>
		<h3><?php echo __('Extra profile information for author box', 'virtue-toolkit');?></h3>
		
		<table class="form-table">
	  		<tr>
	    		<th>
	    			<label for="occupation">
	    				<?php _e('Occupation', 'virtue-toolkit');?>
	    			</label>
	    		</th>
	    		<td>
	      			<input type="text" name="occupation" id="occupation" value="<?php echo esc_attr( get_the_author_meta( 'occupation', $user->ID ) ); ?>" class="regular-text" /><br />
	      			<span class="description"><?php _e('Please enter your Occupation.', 'virtue-toolkit');?></span>
	    		</td>
	  		</tr>
	  		<tr>
		    	<th>
		    		<label for="twitter">Twitter</label>
		    	</th>
	    		<td>
	      			<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
	      			<span class="description"><?php _e('Please enter your Twitter username.', 'virtue-toolkit'); ?></span>
	    		</td>
	  		</tr>
	    	<tr>
	    		<th>
	    			<label for="facebook">Facebook</label></th>
	    		<td>
			      	<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
			      	<span class="description"><?php _e('Please enter your Facebook url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
	    		</td>
	  		</tr>
	    	<tr>
		    	<th>
		    		<label for="google">Google Plus</label>
		    	</th>
		    	<td>
		      		<input type="text" name="google" id="google" value="<?php echo esc_attr( get_the_author_meta( 'google', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Google Plus url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
	  		</tr>
	   		<tr>
	    		<th>
	    			<label for="youtube">YouTube</label>
	    		</th>
	    		<td>
		      		<input type="text" name="youtube" id="youtube" value="<?php echo esc_attr( get_the_author_meta( 'youtube', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your YouTube url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
	    	<tr>
		    	<th>
		    		<label for="flickr">Flickr</label>
		    	</th>
		    	<td>
		      		<input type="text" name="flickr" id="flickr" value="<?php echo esc_attr( get_the_author_meta( 'flickr', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Flickr url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
	    	<tr>
	    		<th>
	    			<label for="vimeo">Vimeo</label>
	    		</th>
		    	<td>
		      		<input type="text" name="vimeo" id="vimeo" value="<?php echo esc_attr( get_the_author_meta( 'vimeo', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Vimeo url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
	    	<tr>
		    	<th>
		    		<label for="linkedin">Linkedin</label>
		    	</th>
		    	<td>
		      		<input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Linkedin url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
	    	<tr>
		    	<th>
		    		<label for="dribbble">Dribbble</label>
		    	</th>
		    	<td>
		      		<input type="text" name="dribbble" id="dribbble" value="<?php echo esc_attr( get_the_author_meta( 'dribbble', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Dribbble url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
		    <tr>
		    	<th>
		    		<label for="pinterest">Pinterest</label>
		    	</th>
		    	<td>
		      		<input type="text" name="pinterest" id="pinterest" value="<?php echo esc_attr( get_the_author_meta( 'pinterest', $user->ID ) ); ?>" class="regular-text" /><br />
		      		<span class="description"><?php _e('Please enter your Pinterest url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
		    	</td>
		  	</tr>
		  	<tr>
		    	<th>
		    		<label for="instagram">Instagram</label>
		    	</th>
	    		<td>
	      			<input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" /><br />
	      			<span class="description"><?php _e('Please enter your Instagram url. (be sure to include http://)', 'virtue-toolkit'); ?></span>
	    		</td>
	  		</tr>
		</table>
	<?php
	} 
}

add_action( 'personal_options_update', 'virtue_toolkit_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'virtue_toolkit_save_extra_profile_fields' );

function virtue_toolkit_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    if(isset($_POST['occupation'])){
  		update_user_meta( $user_id, 'occupation', wp_filter_post_kses($_POST['occupation']) );
  	}
  	if(isset($_POST['twitter'])) {
    	update_user_meta( $user_id, 'twitter', sanitize_title(wp_unslash($_POST['twitter']) ));
    }
    if(isset($_POST['facebook'])) {
  		update_user_meta( $user_id, 'facebook', esc_url_raw($_POST['facebook']) );
  	}
  	if(isset($_POST['google'])) {
  		update_user_meta( $user_id, 'google', esc_url_raw($_POST['google']) );
  	}
  	if(isset($_POST['youtube'])) {
  		update_user_meta( $user_id, 'youtube', esc_url_raw($_POST['youtube']) );
  	}
  	if(isset($_POST['flickr'])) {
  		update_user_meta( $user_id, 'flickr', esc_url_raw($_POST['flickr']) );
  	}
  	if(isset($_POST['vimeo'])) {
  		update_user_meta( $user_id, 'vimeo', esc_url_raw($_POST['vimeo']) );
  	}
  	if(isset($_POST['linkedin'])) {
  		update_user_meta( $user_id, 'linkedin', esc_url_raw($_POST['linkedin']) );
  	}
  	if(isset($_POST['dribbble'])) {
  		update_user_meta( $user_id, 'dribbble', esc_url_raw($_POST['dribbble']) );
  	}
  	if(isset($_POST['pinterest'])) {
  		update_user_meta( $user_id, 'pinterest', esc_url_raw($_POST['pinterest']) );
  	}
  	if(isset($_POST['instagram'])) {
  		update_user_meta( $user_id, 'instagram', esc_url_raw($_POST['instagram']) );
  	}
}