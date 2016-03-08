var openMobile = function(casper){
	casper.userAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
	casper.thenOpen("https://m.facebook.com", function(){
		casper.echo("Success: facebook mobile page opened");
	});
};
exports.openMobile = openMobile;

var login = function(casper, user)
{
	casper.sendKeys('input[name="email"]', user.email);
	casper.sendKeys('input[name="pass"]', user.pass);
	casper.click('input[name="login"]');
	casper.waitForSelector("a[href*='/logout']", function(){
		casper.echo("Success: Loged In to facebook.");
	});
};
exports.login = login;

var logout = function(casper){
		casper.waitForSelector("a[href*='/logout']", function(){
		casper.click("a[href*='/logout']");
	});
	casper.waitForSelector('input[name="email"]', function(){
		casper.echo("Success: Loged Out from facebook.")
	});
};
exports.logout = logout;

var isLogedIn = function(casper){
	if (casper.exists('input[name="email"]')){
		return (false);
	}
	return (true);
};
exports.isLogedIn = isLogedIn;

var createAccount = function(casper, user){
		casper.waitForSelector('input[name="login"]', function(){
		casper.capture('before.png');
		if (casper.exists('a[href*="en_US"]'))
		{
			casper.click('a[href*="en_US"]');
			casper.wait(3000);
		}
		casper.click('a[href$="refid=8"]');
		casper.wait(4000, function(){
			casper.fillSelectors('form#mobile-reg-form', {
				'input[name="firstname"]': 		user.firstname,
				'input[name="lastname"]': 		user.lastname,
				'input[name="reg_email__"]': 	user.email,
				'[name="sex"]': 				1,
				'input[name="birthday_day"]': 	user.birth.day,
				'input[name="birthday_month"]': user.birth.month,
				'input[name="birthday_year"]': 	user.birth.year,
				'input[name="reg_passwd__"]': 	user.pass
			}, true);
		});
	});
};
exports.createAccount = createAccount;

var acceptToken = function(casper){
	casper.capture("coucou.png");
};
exports.acceptToken = acceptToken;
