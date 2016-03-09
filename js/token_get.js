var user = {
	'firstname': 'Henry',
	'lastname': 'Leblanc',
	'email': 'velardegav519@gmail.com',
	'gender': 0,
	'birth': {
		'day': 02,
		'month': 01,
		'year': 1993
	},
	'pass': 'UQBpqmBr384'
};

var facebook = require('facebook.js');
var casper = require('casper').create({
	verbose: true,
	logLevel: "debug"
});

casper.start();

facebook.openMobile(casper);
casper.then(function(){
	if (facebook.isLogedIn(casper)){
		this.logout(casper);
	}
	facebook.login(this, user);
	casper.then(function(){
		casper.capture("logedin.png");
	});
});
casper.thenOpen("https://goo.gl/oPCccY", function(){
	facebook.getToken(casper);
});

casper.run();
