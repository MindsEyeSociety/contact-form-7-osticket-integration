<div class="wrap">

	<h2><?php esc_html_e( 'OSTicket Settings' , 'contact-form-7-osticket-integration');?></h2>


	<div class="postbox-container" style="">
		<div id="normal-sortables" class="meta-box-sortables ui-sortable">
			<div id="referrers" class="postbox ">
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class="hndle"><span><?php esc_html_e( 'Settings' , 'contact-form-7-osticket-integration');?></span></h3>
				<form name="cf7_osticket_admin" id="cf7_osticket_admin" action="<?php echo esc_url( cf7_osticket_admin::get_page_url() ); ?>" method="POST">
					<div class="inside">
						<table cellspacing="0">
							<tbody>
							<tr>
								<th width="20%" align="left" scope="row"><?php esc_html_e('OSTicket Host', 'contact-form-7-osticket-integration');?></th>
								<td width="5%"/>
								<td align="left">
									<span><input id="host" name="host" type="text" size="15" value="<?php echo esc_attr( cf7_osticket_settings::getHost() ); ?>" class="regular-text code"></span>
								</td>
							</tr>

							<tr>
								<th width="20%" align="left" scope="row"><?php esc_html_e('OSTicket Path', 'contact-form-7-osticket-integration');?></th>
								<td width="5%"/>
								<td align="left">
									<span><input id="path" name="path" type="text" size="15" value="<?php echo esc_attr( cf7_osticket_settings::getPath() ); ?>" class="regular-text code"></span>
								</td>
							</tr>

							<tr>
								<th width="20%" align="left" scope="row"><?php esc_html_e('OSTicket API Key', 'contact-form-7-osticket-integration');?></th>
								<td width="5%"/>
								<td align="left">
									<span><input id="api_key" name="api_key" type="text" size="15" value="<?php echo esc_attr( cf7_osticket_settings::getApiKey() ); ?>" class="regular-text code"></span>
								</td>
							</tr>

							</tbody>
						</table>
					</div>
					<div id="major-publishing-actions">
						<?php wp_nonce_field(cf7_osticket_admin::NONCE) ?>
						<div id="publishing-action">
							<input type="hidden" name="action" value="enter-key">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'contact-form-7-osticket-integration');?>">

						</div>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>