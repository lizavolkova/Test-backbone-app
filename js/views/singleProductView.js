//Namespace app
var app = app || {};

//define how a single product renders
app.singleProductView = Backbone.View.extend({
	//define which template to use
	template: _.template( $('#productElement').html() ),
	className: 'single-product',
	//convert used model data to JSON and then render to above template
	render: function() {
		var productTemplate = this.template(this.model.toJSON());
		this.$el.html(productTemplate);
		return this;
		/*this.$el.html('demo content');
		return this;*/
	},

	//listen for changes to model before rendering; fetch data per model urlRoot
	initialize: function() {
		this.model.fetch();
		this.listenTo(this.model, 'change', this.render);
		//console.log('single product view initialized');
	},

	//when a user enters a product id, fetch requested product data
	events: {
		'submit #myform' : 'fetchProduct'
	},

	fetchProduct: function(e) {
		//e.preventDefault();
		var prodId = $('#idInput').val();
		//var prodId = $(e.target).val();
		//console.log(prodId);
		//console.log('test');
		this.model.set('id',prodId);
		this.model.fetch();
		//console.log(this.model.id);
	}

});