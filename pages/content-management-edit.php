<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".Jntp_Table."
	WHERE `Jntp_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$Jntp_errors = array();
	$Jntp_success = '';
	$Jntp_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".Jntp_Table."`
		WHERE `Jntp_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'Jntp_text' => $data['Jntp_text'],
		'Jntp_link' => $data['Jntp_link'],
		'Jntp_order' => $data['Jntp_order'],
		'Jntp_status' => $data['Jntp_status'],
		'Jntp_date' => $data['Jntp_date'],
		'Jntp_group' => $data['Jntp_group'],
		'Jntp_dateend' => $data['Jntp_dateend'],
		'Jntp_id' => $data['Jntp_id']
	);
}
// Form submitted, check the data
if (isset($_POST['Jntp_form_submit']) && $_POST['Jntp_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Jntp_form_edit');
	
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
		$sSql = $wpdb->prepare(
				"UPDATE `".Jntp_Table."`
				SET `Jntp_text` = %s,
				`Jntp_link` = %s,
				`Jntp_order` = %s,
				`Jntp_status` = %s,
				`Jntp_group` = %s,
				`Jntp_dateend` = %s
				WHERE Jntp_id = %d
				LIMIT 1",
				array($form['Jntp_text'], $form['Jntp_link'], $form['Jntp_order'], $form['Jntp_status'], $form['Jntp_group'], $form['Jntp_dateend'], $did)
			);

		$wpdb->query($sSql);
		
		$Jntp_success = 'Details was successfully updated.';
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
      <h3>Update details</h3>
	  
	  <label for="tag-a">News</label>
		<textarea name="Jntp_text" id="Jntp_text" cols="130" rows="2"><?php echo esc_html(stripslashes($form['Jntp_text'])); ?></textarea>
		<p>Please enter your ticker news.</p>
		
		<label for="tag-a">Link</label>
		<input name="Jntp_link" type="text" id="Jntp_link" value="<?php echo $form['Jntp_link']; ?>" size="133" maxlength="1024" />
		<p>Please enter your link.</p>
		
		<label for="tag-a">Order</label>
		<input name="Jntp_order" type="text" id="Jntp_order" value="<?php echo $form['Jntp_order']; ?>" size="20" maxlength="3" />
		<p>Please enter your display order.</p>
	  
		<label for="tag-a">Display</label>
		<select name="Jntp_status" id="Jntp_status">
			<option value='YES' <?php if($form['Jntp_status'] == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
			<option value='NO' <?php if($form['Jntp_status'] == 'NO') { echo "selected='selected'" ; } ?>>No</option>
		</select>
		<p>Please select your display status.</p>
		
		<label for="tag-a">Group</label>
	    <select name="Jntp_group" id="Jntp_group">
			<option value=''>Select</option>
			<option value='GROUP1' <?php if($form['Jntp_group'] == 'GROUP1') { echo "selected='selected'" ; } ?>>GROUP1</option>
			<option value='GROUP2' <?php if($form['Jntp_group'] == 'GROUP2') { echo "selected='selected'" ; } ?>>GROUP2</option>
			<option value='GROUP3' <?php if($form['Jntp_group'] == 'GROUP3') { echo "selected='selected'" ; } ?>>GROUP3</option>
			<option value='GROUP4' <?php if($form['Jntp_group'] == 'GROUP4') { echo "selected='selected'" ; } ?>>GROUP4</option>
			<option value='GROUP5' <?php if($form['Jntp_group'] == 'GROUP5') { echo "selected='selected'" ; } ?>>GROUP5</option>
			<option value='GROUP6' <?php if($form['Jntp_group'] == 'GROUP6') { echo "selected='selected'" ; } ?>>GROUP6</option>
			<option value='GROUP7' <?php if($form['Jntp_group'] == 'GROUP7') { echo "selected='selected'" ; } ?>>GROUP7</option>
			<option value='GROUP8' <?php if($form['Jntp_group'] == 'GROUP8') { echo "selected='selected'" ; } ?>>GROUP8</option>
			<option value='GROUP9' <?php if($form['Jntp_group'] == 'GROUP9') { echo "selected='selected'" ; } ?>>GROUP9</option>
			<option value='GROUP10' <?php if($form['Jntp_group'] == 'GROUP10') { echo "selected='selected'" ; } ?>>GROUP10</option>
		</select>
		<p>Please select available group for your news.</p>
		
		<label for="tag-title">Expiration date</label>
		<input name="Jntp_dateend" type="text" id="Jntp_dateend" value="<?php echo substr($form['Jntp_dateend'],0,10); ?>" maxlength="10" />
		<p>Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-31 : Is equal to no expire.</p>
	  
      <input name="Jntp_id" id="Jntp_id" type="hidden" value="<?php echo $form['Jntp_id']; ?>">
      <input type="hidden" name="Jntp_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Update Details" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_Jntp_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_Jntp_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('Jntp_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo Jntp_LINK; ?></p>
</div>