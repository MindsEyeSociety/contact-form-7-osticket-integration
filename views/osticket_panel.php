<?php
/**
 * @author Joe Terranova <joeterranova@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
?>
<h3><?php echo esc_html( __( 'OSTicket Settings', 'contact-form-7-osticket-integration' ) ); ?></h3>

<div class="contact-form-editor-box-osticket">

<?php if(!function_exists('curl_version')){ ?> <div class="notice>The Curl extension is required</div> <?php } ?>
<?php if(!function_exists('json_encode')){ ?> <div class="notice>The JSON extension is required</div> <?php } ?>

	<p><label for="enable-osticket"><input type="checkbox" id="enable-osticket" name="enable-osticket" class="toggle-form-table" value="1"<?php echo ( ! empty( $osticket['enable'] ) ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html( __( 'Enable OSTicket processing', 'contact-form-7-osticket-integration' ) ); ?></label></p>

<fieldset>

	<legend><?php echo esc_html( __( "Use parameters to set the api parameters e.g. name=[your-name]&email=[your-email]&subject=[your-subject]&message=[your-message]&topicId=[your-topic]" ) ); ?></legend>

	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="action"><?php echo esc_html( __( 'Parameters', 'contact-form-7-osticket-integration' ) ); ?></label>
			</th>
			<td>
				<input type="text" id="osticket-parameters" name="osticket-parameters" class="large-text code" size="100" value="<?php echo esc_attr( $osticket['parameters'] ); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="action"><?php echo esc_html( __( 'Skip E-Mail', 'contact-form-7-osticket-integration' ) ); ?></label>
			</th>
			<td>
				<input type="checkbox" id="osticket-skipmail" name="osticket-skipmail" value="1" <?php if($osticket['skipmail']) echo "checked=checked" ?> />
			</td>
		</tr>
		</tbody>
	</table>

</fieldset>

</div>