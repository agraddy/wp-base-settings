<div class="wrap setting_page" id="<?php echo $action; ?>_page">
	<h1 class="wp-heading-inline"><?php echo $title; ?></h1>

	<div id="ajax_message"></div>

	<form action="admin-ajax.php" method="post">
		<?php wp_nonce_field( $action ); ?>
		<input name="action" type="hidden" value="<?php echo $action; ?>" />

		<table class="form-table">
			<?php for($i = 0; $i < count($titles); $i++): ?>
			<tr class="form-field">
				<th scope="row"><label><?php echo $titles[$i]; ?>:</th>
				<td>
					<?php if($elements[$i] == 'select'): ?>
					<?php echo $codes[$i]; ?>
					<p class="description"><?php echo $descriptions[$i]; ?></p>
					<?php elseif($elements[$i] == 'checkbox'): ?>
						<label><input type="checkbox" name="<?php echo $names[$i]; ?>" value="yes" <?php echo ($values[$i] == 'yes') ? 'checked' : ''; ?> /> <?php echo $descriptions[$i]; ?></label>
					<?php elseif($elements[$i] == 'text'): ?>
					<input type="text" name="<?php echo $names[$i]; ?>" value="<?php echo $values[$i]; ?>" />
					<p class="description"><?php echo $descriptions[$i]; ?></p>
					<?php elseif($elements[$i] == 'textarea'): ?>
					<textarea name="<?php echo $names[$i]; ?>"><?php echo $values[$i]; ?></textarea>
					<p class="description"><?php echo $descriptions[$i]; ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<?php endfor; ?>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="Update">
			<span class="spinner"></span>
		</p>
	</form>
</div>
