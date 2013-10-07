<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$Jntp_errors = array();
$Jntp_success = '';
$Jntp_error_found = FALSE;

// Preset the form fields
$form = array(
	'Jntp_text' => '',
	'Jntp_link' => '',
	'Jntp_order' => '',
	'Jntp_status' => '',
	'Jntp_date' => '',
	'Jntp_group' => '',
	'Jntp_dateend' => '',
	'Jntp_id' => ''
);

// Form submitted, check the data
if (isset($_POST['Jntp_form_submit']) && $_POST['Jntp_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Jntp_form_add');
	
	$form['Jntp_text'] = isset($_POST['Jntp_text']) ? $_POST['Jntp_text'] : '';
	if ($form['Jntp_text'] == '')
	{
		$Jntp_errors[] = __('Please enter your ticker news.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}
	
	$form['Jntp_link'] = isset($_POST['Jntp_link']) ? $_POST['Jntp_link'] : '';
	if ($form['Jntp_link'] == '')
	{
		$Jntp_errors[] = __('Please enter your link.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}

	$form['Jntp_order'] = isset($_POST['Jntp_order']) ? $_POST['Jntp_order'] : '';
	if ($form['Jntp_order'] == '')
	{
		$Jntp_errors[] = __('Please enter your display order.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}
	
	$form['Jntp_status'] = isset($_POST['Jntp_status']) ? $_POST['Jntp_status'] : '';
	if ($form['Jntp_status'] == '')
	{
		$Jntp_errors[] = __('Please select your display status.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}
		
	$form['Jntp_group'] = isset($_POST['Jntp_group']) ? $_POST['Jntp_group'] : '';
	if ($form['Jntp_group'] == '')
	{
		$Jntp_errors[] = __('Please select available group for your news.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}
	
	$form['Jntp_dateend'] = isset($_POST['Jntp_dateend']) ? $_POST['Jntp_dateend'] : '';
	if ($form['Jntp_dateend'] == '')
	{
		$Jntp_errors[] = __('Please enter the expiration date in this format YYYY-MM-DD.', Jntp_UNIQUE_NAME);
		$Jntp_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($Jntp_error_found == FALSE)
	{
		$Jntp_date = date('Y-m-d H:i:s');
		$sql = $wpdb->prepare(
			"INSERT INTO `".Jntp_Table."`
			(`Jntp_text`, `Jntp_link`, `Jntp_order`, `Jntp_status`, `Jntp_date`, `Jntp_group`, `Jntp_dateend`)
			VALUES(%s, %s, %s, %s, %s, %s, %s)",
			array($form['Jntp_text'], $form['Jntp_link'], $form['Jntp_order'], $form['Jntp_status'], $Jntp_date, $form['Jntp_group'], $form['Jntp_dateend'])
		);
		$wpdb->query($sql);

		$Jntp_success = __('New details was successfully added.', Jntp_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'Jntp_text' => '',
			'Jntp_link' => '',
			'Jntp_order' => '',
			'Jntp_status' => '',
			'Jntp_date' => '',
			'Jntp_group' => '',
			'Jntp_dateend' => '',
			'Jntp_id' => ''
		);
	}
}

if ($Jntp_error_found == TRUE && isset($Jntp_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $Jntp_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($Jntp_error_found == FALSE && strlen($Jntp_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $Jntp_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=jquery-news-ticker">Click here</a> to view the details</strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/jquery-news-ticker/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo Jntp_TITLE; ?></h2>
	<form name="Jntp_form" method="post" action="#" onsubmit="return _Jntp_submit()"  >
      <h3>Add details</h3>
      
		<label for="tag-a">News</label>
		<textarea name="Jntp_text" id="Jntp_text" cols="130" rows="2"></textarea>
		<p>Please enter your ticker news.</p>
		
		<label for="tag-a">Link</label>
		<input name="Jntp_link" type="text" id="Jntp_link" value="#" size="133" maxlength="1024" />
		<p>Please enter your link.</p>
		
		<label for="tag-a">Order</label>
		<input name="Jntp_order" type="text" id="Jntp_order" value="1" size="20" maxlength="3" />
		<p>Please enter your display order.</p>
	  
		<label for="tag-a">Display</label>
		<select name="Jntp_status" id="Jntp_status">
			<option value='YES' selected="selected">Yes</option>
			<option value='NO'>No</option>
		</select>
		<p>Please select your display status.</p>
		
		<label for="tag-a">Group</label>
	    <select name="Jntp_group" id="Jntp_group">
			<option value=''>Select</option>
			<option value='GROUP1' selected="selected">GROUP1</option>
			<option value='GROUP2'>GROUP2</option>
			<option value='GROUP3'>GROUP3</option>
			<option value='GROUP4'>GROUP4</option>
			<option value='GROUP5'>GROUP5</option>
			<option value='GROUP6'>GROUP6</option>
			<option value='GROUP7'>GROUP7</option>
			<option value='GROUP8'>GROUP8</option>
			<option value='GROUP9'>GROUP9</option>
			<option value='GROUP10'>GROUP10</option>
		</select>
		<p>Please select available group for your news.</p>
		
		<label for="tag-title">Expiration date</label>
		<input name="Jntp_dateend" type="text" id="Jntp_dateend" value="9999-12-31" maxlength="10" />
		<p>Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-31 : Is equal to no expire.</p>
	  
      <input name="Jntp_id" id="Jntp_id" type="hidden" value="">
      <input type="hidden" name="Jntp_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="Submit" type="submit" />
        <input name="publish" lang="publish" class="button" onclick="_Jntp_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button" onclick="_Jntp_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('Jntp_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo Jntp_LINK; ?></p>
</div>