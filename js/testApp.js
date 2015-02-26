//create new product with set product id
var demoProduct = new app.singleProduct({id: 591314});

//fetch product information
demoProduct.fetch();

//define which model to use to render single product
var demoProductView = new app.singleProductView({model:demoProduct});

//render product data to DOM
$('#productList').html(demoProductView.render().el).fadeIn('slow');

