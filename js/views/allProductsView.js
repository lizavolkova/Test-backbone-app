//Namespace app
var app = app || {};



app.allProductsView = Backbone.View.extend({

	tagName: "section",


	render: function() {
		this.collection.each(this.addProduct, this);
		return this;

		/*this.collection.each(function(product){
			var productView = new app.singleProductView ({model});
			console.log(product);
		});*/
	},


	addProduct: function(product) {
		var productView = new app.singleProductView({model: product})
		this.$el.append(productView.render().el);
		//console.log('addProduct called');
	}	


});