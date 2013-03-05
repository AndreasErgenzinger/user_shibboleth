<form id="user_shibboleth" action="#" method="post">
	<fieldset class="personalblock">
		<legend><strong>Shibboleth Authentication</strong></legend>
		<label for="sessions_handler_url">Sessions HandlerURL:</label><input type="text" id="sessions_handler_url" name="sessions_handler_url" value="<?php print($_['sessions_handler_url']); ?>"><br/>
		<label for="session_initiator_location">SessionInitiator Location:</label><input type="text" id="session_initiator_location" name="session_initiator_location" value="<?php print($_['session_initiator_location']); ?>"><br/>
		<label for="salt">Salt:</label><input type="text" id="salt" name="salt" value="<?php print($_['salt']); ?>"><br/>
		<label for="ldap_mail_suffix">LDAP Mail Suffix:</label><input type="text" id="ldap_mail_suffix" name="ldap_mail_suffix" value="<?php print($_['ldap_mail_suffix']); ?>"><br/>
		<input type="submit" value="Save" />
	</fieldset>
</form>

