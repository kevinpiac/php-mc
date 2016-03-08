var user = {
	'firstname': 'Henry',
	'lastname': 'Leblanc',
	'email': 'hyleblanc@gmail.com',
	'gender': 0,
	'birth': {
		'day': 02,
		'month': 01,
		'year': 1993
	},
	'pass': '7bmHP53g'
};

var facebook = require('facebook.js');
var casper = require('casper').create({
	verbose: true,
	logLevel: "debug"
});

casper.start();

facebook.openMobile(casper);
casper.then(function(){
	facebook.createAccount(casper, user);
	this.then(function(){
		this.echo("Success: Account Successfully Create, waiting validation !");
		this.capture('test.png');
	});
});

casper.run();
