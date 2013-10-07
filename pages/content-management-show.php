<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if (isset($_POST['frm_Jntp_display']) && $_POST['frm_Jntp_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$Jntp_success = '';
	$Jntp_success_msg = FALSE;
	
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".Jntp_Table."
		WHERE `Jntp_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			check_admin_referer('Jntp_form_show');
			
			$sSql = $wpdb->prepare("DELETE FROM `".Jntp_Table."`
					WHERE `Jntp_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			$Jntp_success_msg = TRUE;
			$Jntp_success = __('Selected record was successfully deleted.', Jntp_UNIQUE_NAME);
		}
	}
	
	if ($Jntp_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $Jntp_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo Jntp_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=jquery-news-ticker&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".Jntp_Table."` order by Jntp_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/jquery-news-ticker/pages/setting.js"></script>
		<form name="frm_Jntp_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Jntp_group_item[]" /></th>
			<th scope="col">News</th>
			<th scope="col">Group</th>
			<th scope="col">Display</th>
			<th scope="col">Expiration</th>
			<th scope="col">Order</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="Jntp_group_item[]" /></th>
			<th scope="col">News</th>
			<th scope="col">Group</th>
			<th scope="col">Display</th>
			<th scope="col">Expiration</th>
			<th scope="col">Order</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['Jntp_id']; ?>" name="Jntp_group_item[]"></td>
						<td><?php echo stripslashes($data['Jntp_text']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=jquery-news-ticker&amp;ac=edit&amp;did=<?php echo $data['Jntp_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_Jntp_delete('<?php echo $data['Jntp_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
						</td>
						<td><?php echo $data['Jntp_group']; ?></td>
						<td><?php echo $data['Jntp_status']; ?></td>
						<td><?php echo substr($data['Jntp_dateend'],0,10); ?></td>
						<td><?php echo $data['Jntp_order']; ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="6" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('Jntp_form_show'); ?>
		<input type="hidden" name="frm_Jntp_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=jquery-news-ticker&amp;ac=add">Add New</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo Jntp_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:8px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget to your sidebar.</li>
		<li>Add the ticker in posts or pages using short code.</li>
		<li>Add directly in to the theme using PHP code.</li>
	</ol>
	<p class="description"><?php echo Jntp_LINK; ?></p>
	</div>
</div>