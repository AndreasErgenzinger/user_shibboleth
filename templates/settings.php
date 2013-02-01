<form id="user_shibboleth" action="#" method="post">
	<fieldset class="personalblock">
		<legend><strong>Shibboleth Authentication</strong></legend>
		<label for="sessions_handler_url">Sessions handlerURL:<input type="text" id="sessions_handler_url" name="sessions_handler_url" value="<?php print($_['sessions_handler_url']); ?>"></label><br/>
		<label for="session_initiator_location">SessionInitiator location:<input type="text" id="session_initiator_location" name="session_initiator_location" value="<?php print($_['session_initiator_location']); ?>"></label><br/>
		<label for="logout_initiator_location">LogoutInitiator location:<input type="text" id="logout_initiator_location" name="logout_initiator_location" value="<?php print($_['logout_initiator_location']); ?>"></label><br/>
		<input type="submit" value="Save" />
	</fieldset>
</form>

