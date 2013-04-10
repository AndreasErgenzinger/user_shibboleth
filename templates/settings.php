<form id="user_shibboleth" action="#" method="post">
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Shibboleth User Backend'));?></strong></legend>
		<label for="sessions_handler_url">Sessions HandlerURL:</label><input type="text" id="sessions_handler_url" name="sessions_handler_url" value="<?php p($_['sessions_handler_url']); ?>" title="<?php p($l->t('Value from shibboleth2.xml file.'));?>"><br/>
		<label for="session_initiator_location">SessionInitiator Location:</label><input type="text" id="session_initiator_location" name="session_initiator_location" value="<?php p($_['session_initiator_location']); ?>" title="<?php p($l->t('Value from shibboleth2.xml file.'));?>"><br/>
		<label for="federation_name"><?php p($l->t('Federation Name'));?>:</label><input type="text" id="federation_name" name="federation_name" value="<?php p($_['federation_name']); ?>" title="<?php p($l->t('Optional value shown on the login button.'));?>"><br/>
		<label for="external_user_quota"><?php p($l->t('Quota'));?>:</label><input type="text" id="external_user_quota" name="external_user_quota" value="<?php p($_['external_user_quota']); ?>" title="<?php p($l->t('Amount of disk space granted to external Shibboleth users.'));?>"><br/>
		<input type="checkbox" id="enforce_domain_similarity_checkbox" title="<?php p($l->t('Reject users with differing email address domain and IdP domain.'));?>" /><label class="shib_check_box" for="enforce_domain_similarity_checkbox"><?php p($l->t('Enforce Domain Similarity'));?></label><br/>
		<input type="hidden" id="enforce_domain_similarity" value="<?php p($_['enforce_domain_similarity']); ?>" name="enforce_domain_similarity">
		<input type="checkbox" id="link_to_ldap_backend_checkbox" title="<?php p($l->t('Map Shibboleth users to LDAP accounts, based on the mail attribute.'));?>" /><label class="shib_check_box" for="link_to_ldap_backend_checkbox"><?php p($l->t('Link to LDAP Backend'));?></label><br/>
		<input type="hidden" id="link_to_ldap_backend" value="<?php p($_['link_to_ldap_backend']); ?>" name="link_to_ldap_backend">
		 <input type="submit" value="Save" />
	</fieldset>
</form>

