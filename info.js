facebook = require('facebook');

var user = {
	'firstname': 'Henry',
	'lastname': 'Leblanc',
	'email': 'yleblanc@gmail.com',
	'gender': 0,
	'birth': {
		'day': 02,
		'month': 01,
		'year': 1993
	},
	'pass': 'FUCK'
};

var casper = require('casper').create({
	clientScripts: ['./jquery.min.js'],
	verbose: true,
	logLevel: "debug"
});
casper.start();
casper.fbLogin = function(){
	this.sendKeys('input[name="email"]', "kevinpiac@gmail.com");
	this.sendKeys('input[name="pass"]', "grossemerde2B");
	this.click('input[name="login"]');
	this.waitForSelector("a[href*='/logout']", function(){
		this.echo("Success: Loged In to facebook.");
	});
};

casper.fbLogout = function(){
	this.waitForSelector("a[href*='/logout']", function(){
		this.click("a[href*='/logout']");
	});
	this.waitForSelector('input[name="email"]', function(){
		this.echo("Success: Loged Out from facebook.")
	});
};

casper.fbNewAct = function(user){
	this.waitForSelector('input[name="login"]', function(){
		this.capture('before.png');
		if (this.exists('a[href*="en_US"]'))
		{
			this.click('a[href*="en_US"]');
			this.wait(3000);
		}
		this.click('a[href$="refid=8"]');
		this.wait(4000, function(){
			this.fillSelectors('form#mobile-reg-form', {
				'input[name="firstname"]': 		user.firstname,
				'input[name="lastname"]': 		user.lastname,
				'input[name="reg_email__"]': 	user.email,
				'[name="sex"]': 				1,
				'input[name="birthday_day"]': 	user.birth.day,
				'input[name="birthday_month"]': user.birth.month,
				'input[name="birthday_year"]': 	user.birth.year,
				'input[name="reg_passwd__"]': 			user.pass
			}, false);
			this.capture('check.png')
		});
	});
};
casper.userAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
casper.thenOpen("https://m.facebook.com", function(){
//	this.fbNewAct(user);
	facebook.login(this);
	facebook.logout(this);
	facebook.createAccount(this, user);
});
casper.run();
