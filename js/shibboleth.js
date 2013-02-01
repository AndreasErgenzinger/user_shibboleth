(function() {
	var shib = document.createElement('script');
	shib.type = 'text/javascript';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(shib);
})();

$(document).ready(function(){
	
	var loginMsg = "Log in with Shibboleth.";

	var link = window.location.pathname;
	link = link.substring(0,link.lastIndexOf("/")+1) + "apps/user_shibboleth/login.php";

	$('<a href="'+link+'" class="login_shibboleth"><div class="login_shibboleth">'+loginMsg+'<img class="login_shibboleth" src="' + OC.imagePath('user_shibboleth', 'shibboleth_logo.png') + '" title="'+ loginMsg +'" alt="'+ loginMsg +'" /></div></a>').appendTo('form');

})


