(function() {
	var shib = document.createElement('script');
	shib.type = 'text/javascript';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(shib);
})();

$(document).ready(function(){
	document.getElementById("logout").href="/owncloud/apps/user_shibboleth/logout.php";
})
