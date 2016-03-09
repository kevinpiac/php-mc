var facebook = require('facebook.js');
var casper = require('casper').create({
	verbose: false,
//	logLevel: "debug"
});
var user = {
	'email': casper.cli.get(0),
	'pass': casper.cli.get(1),
	'fb_account_id': casper.cli.get(2)
};
casper.start();

facebook.openMobile(casper);
casper.then(function(){
	if (facebook.isLogedIn(casper)){
		facebook.logout(casper);
	}
	facebook.login(this, user);
});
casper.thenOpen("https://goo.gl/oPCccY", function(){
	facebook.getToken(casper, user.fb_account_id);
});

casper.run();
