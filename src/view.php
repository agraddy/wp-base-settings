<div class="wrap setting_page" id="<?php echo $action; ?>_page">
	<h1 class="wp-heading-inline"><?php echo $title; ?></h1>

	<div id="ajax_message"></div>

	<form action="admin-ajax.php" method="post">
		<?php wp_nonce_field( $action ); ?>
		<input name="action" type="hidden" value="<?php echo $action; ?>" />

		<table class="form-table">
			<?php for($i = 0; $i < count($titles); $i++): ?>
			<?php if($elements[$i] == 'table_expand'): ?>
			<tr class="form-field">
				<td></td>
				<?php for($k = 0; $k < count($codes[$i]['fields']); $k++): ?>
				<td><strong><?php echo $codes[$i]['placeholders'][$k]; ?></strong></td>
				<?php endfor; ?>
			</tr>
			<?php for($j = 0; $j < count($codes[$i]['values'][$codes[$i]['fields'][0]]); $j++): ?>
			<tr class="form-field">
				<?php if($j == 0): ?>
				<th scope="row"><label><?php echo $titles[$i]; ?>:</th>
				<?php else: ?>
				<td> <a href="#" class="button delete_row">X</a> </td> 
				<?php endif; ?>
				<?php for($k = 0; $k < count($codes[$i]['fields']); $k++): ?>
				<td>
					<input type="text" name="<?php echo $codes[$i]['fields'][$k]; ?>[]" value="<?php echo $codes[$i]['values'][$codes[$i]['fields'][$k]][$j]; ?>" placeholder="<?php echo $codes[$i]['placeholders'][$k]; ?>" />
				</td>
				<?php endfor; ?>
			</tr>
			<?php endfor; ?>
			<tr class="form-field">
				<td></td>
				<td><a href="#" class="button add_row"><?php echo $codes[$i]['button']; ?></a></td>
			</tr>
			<?php else: ?>
			<tr class="form-field">
				<th scope="row"><label><?php echo $titles[$i]; ?>:</th>
				<td>
					<?php if($elements[$i] == 'radio'): ?>
						<?php for($j = 0; $j < count($extras[$i]['labels']); $j++): ?>
						<label><input type="radio" name="<?php echo $names[$i]; ?>" value="<?php echo esc_attr($extras[$i]['values'][$j]); ?>" <?php echo ($values[$i] == $extras[$i]['values'][$j]) ? 'checked' : ''; ?> /> <?php echo esc_html($extras[$i]['labels'][$j]); ?></label><br>
						<?php endfor; ?>
						<?php echo $descriptions[$i]; ?>
					<?php echo $codes[$i]; ?>
					<p class="description"><?php echo $descriptions[$i]; ?></p>
					<?php elseif($elements[$i] == 'select'): ?>
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
			<?php endif; ?>
			<?php endfor; ?>
		</table>
		<p class="submit">
			<input type="submit" class="button button-primary" value="Update">
			<span class="spinner"></span>
		</p>
	</form>
</div>
