//Namespace app
var app = app || {};

//define structure for a single product
app.singleProduct = Backbone.Model.extend({
	defaults: {
		//id: 215393,
		//id: 591314,
		Name: '',
		price: '',
		img: '',
		Description: '',
		cart_url: '',
		Fsc: ''

	},
	//urlRoot: ROOT+'/apicall.php'
	urlRoot: 'http://bterra.net/skincare/apicall.php'
});