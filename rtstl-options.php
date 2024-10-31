<?php

function rtstl_register_menu_page() {
	
	add_options_page(
		'Related Topics Settings',
		'Related Topics Settings',
		'manage_options',
		'rtstl_options',
		'rtstl_admin_template'
	);
	
}
add_action('admin_menu', 'rtstl_register_menu_page');

function rtstl_admin_template() {

	if(!current_user_can('manage_options'))
		wp_die(__('You do not have sufficient privileges to access this page'));
	?>
	<div class="wrap">
    <h2>Related Topics Settings</h2>
    
    <form method="post" action="options.php">
    	<?php settings_fields( 'rtstl_options' ); ?>
        <?php do_settings_sections( 'rtstl_options' ); ?>
        <?php submit_button(); ?>
    </form>
    </div>
    <?php
}

function rtstl_initialize_admin() {

	if( false == get_option( 'rtstl_options' ) ) {
		add_option('rtstl_options');
	}
	
	add_settings_section(
		'rtstl_setting_section',
		'Display options to customize the Related Topics output on your site.',
		'',
		'rtstl_options'
	);
	
	// Insertion method
	add_settings_field(
		'rtstl_insertion_method',
		'Display of Related Topics',
		'rtstl_insertion_method_display',
		'rtstl_options',
		'rtstl_setting_section'
	);
	
	// Theme selection
	add_settings_field(
		'rtstl_theme',
		'Related Topics Theme',
		'rtstl_theme_display',
		'rtstl_options',
		'rtstl_setting_section'
	);
	
	// Inline theme separator
	add_settings_field(
		'rtstl_inline_separator',
		'Link separator for <em>Inline</em> theme',
		'rtstl_inline_separator_display',
		'rtstl_options',
		'rtstl_setting_section'
	);
	
	// HTML Before
	add_settings_field(
		'rtstl_html_before',
		'HTML to insert before (includes title)',
		'rtstl_html_before_display',
		'rtstl_options',
		'rtstl_setting_section'
	);
	
	// HTML After
	add_settings_field(
		'rtstl_html_after',
		'HTML to insert after list',
		'rtstl_html_after_display',
		'rtstl_options',
		'rtstl_setting_section'
	);
	
	// Register settings
	register_setting(
		'rtstl_options',
		'rtstl_options'
	);

}
add_action('admin_init', 'rtstl_initialize_admin');

// Insertion method field
function rtstl_insertion_method_display() {
	$options = get_option( 'rtstl_options' );
	
	$value = isset($options['rtstl_insertion_method']) ? $options['rtstl_insertion_method'] : '';
	
	echo '<p><input name="rtstl_options[rtstl_insertion_method]" id="rtstl_insertion_method" type="checkbox" value="1" ' . checked( 1, $value, false ) . ' /> Automatically insert at the end of single post views</p>';
	
	echo '<p style="margin-top: 10px; padding: 10px; background: #e7e7e7;border: 1px solid #d7d7d7;"><strong>Else insert manually into your template with the following:</strong>';
	
	echo '<code>' . esc_html('<?php echo rtstl_output(); ?>') . '</code>';
	
	echo '</p>';
	
}

// Theme selection field
function rtstl_theme_display() {
	$options = get_option( 'rtstl_options' );
	
	$value = isset($options['rtstl_theme']) ? $options['rtstl_theme'] : '';
	
	echo '<select name="rtstl_options[rtstl_theme]" id="rtstl_theme">
			<option value="select" ' . selected( $value, 'select', false ) . ' >Select...</option>
			<option value="inline" ' . selected( $value, 'inline', false ) . ' >Inline</option>
			<option value="bullets" ' . selected( $value, 'bullets', false ) . ' >Bulleted</option>
		</select>';
		
	echo ' <em>Default:</em> Inline';
	
}

// Inline theme separator
function rtstl_inline_separator_display() {
	$options = get_option( 'rtstl_options' );
	
	$value = isset($options['rtstl_inline_separator']) ? $options['rtstl_inline_separator'] : '';
	
	echo '<select name="rtstl_options[rtstl_inline_separator]" id="rtstl_inline_separator">
			<option value="select" ' . selected( $value, 'select', false ) . ' >Select...</option>
			<option value="pipe" ' . selected( $value, 'pipe', false ) . ' >| (pipe)</option>
			<option value="space" ' . selected( $value, 'space', false ) . ' >&nbsp; (space)</option>
			<option value="bullet" ' . selected( $value, 'bullet', false ) . ' >&bull; (bullet)</option>
		</select>';
		
	echo ' <em>Default:</em> | (pipe)';
	
}

// HTML Before
function rtstl_html_before_display() {
	$options = get_option( 'rtstl_options' );
	
	$value = isset($options['rtstl_html_before']) ? $options['rtstl_html_before'] : '<h3>Related Topics</h3>';
	
	echo '<textarea cols="40" rows="2" name="rtstl_options[rtstl_html_before]" id="rtstl_html_before">' . esc_textarea( $value ) . '</textarea>';
	
}

// HTML After
function rtstl_html_after_display() {
	$options = get_option( 'rtstl_options' );
	
	$value = isset($options['rtstl_html_after']) ? $options['rtstl_html_after'] : '';
	
	echo '<textarea cols="40" rows="2" name="rtstl_options[rtstl_html_after]" id="rtstl_html_after">' . esc_textarea( $value ) . '</textarea>';
	
}