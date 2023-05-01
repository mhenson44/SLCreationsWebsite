<?php
require('database.php');
   
// Get category ID
$category_id = filter_input(INPUT_GET, 'category_id',
                            FILTER_VALIDATE_INT);
if ($category_id == NULL || $category_id == FALSE) {
    $category_id = 1;
}

// Get name for selected category
$queryCategory = 'SELECT * FROM categories
                     WHERE categoryID = :category_id';
$statement1 = $db->prepare($queryCategory);
$statement1->bindValue(':category_id', $category_id);
$statement1->execute();
$category = $statement1->fetch();
$category_name = $category['categoryName'];
$statement1->closeCursor();

//Get all categories
$queryAllCategories = 'SELECT * FROM categories
                           ORDER BY categoryID';
$statement2 = $db->prepare($queryAllCategories);
$statement2->execute();
$categories = $statement2->fetchAll();
$statement2->closeCursor();

// Get products for selected category
$queryProducts = 'SELECT * FROM products
              WHERE categoryID = :category_id
              ORDER BY productID';
$statement3 = $db->prepare($queryProducts);
$statement3->bindValue(':category_id', $category_id);
$statement3->execute();
$products = $statement3->fetchAll();
$statement3->closeCursor();
?>

<!DOCTYPE html>
<html>
<!-- the head section -->
<head>
<link rel="stylesheet" href="https://obscure-escarpment-2240.herokuapp.com/stylesheets/bcpo-front.css">
<script>var bcpo_product=null;  var bcpo_settings={"shop_currency":"USD","money_format2":"${{amount}} USD","money_format_without_currency":"${{amount}}"};var inventory_quantity = [];if(bcpo_product) { for (var i = 0; i < bcpo_product.variants.length; i += 1) { bcpo_product.variants[i].inventory_quantity = inventory_quantity[i]; }}window.bcpo = window.bcpo || {}; bcpo.cart = {"note":null,"attributes":{},"original_total_price":0,"total_price":0,"total_discount":0,"total_weight":0.0,"item_count":0,"items":[],"requires_shipping":false,"currency":"USD","items_subtotal_price":0,"cart_level_discount_applications":[],"checkout_charge_amount":0}; bcpo.ogFormData = FormData;</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#111111">
   <title>My Guitar Shop</title>
</head>
<!-- the body section -->
<body>
<main>
   <h1>Product List</h1>
   <aside>
<!-- display a list of categories -->
<h2>Categories</h2>
<nav>
<ul>
    <?php foreach ($categories as $category) : ?>
    <li>
         <a href="?category_id=<?php echo
               $category['categoryID']; ?>">
                <?php echo $category['categoryName']; ?>
         </a>
    </li>
    <?php endforeach; ?>
</ul>
</nav>
</aside>

<section>
   <!-- display a table of products -->
   <h2><?php echo $category_name; ?></h2>
   <table>
         <tr>
             <th>Code</th>
             <th>Name</th>
             <th class="right">Price</th>
             <th>&nbsp;</th>
         </tr>

      <?php foreach ($products as $product) : ?>
      <tr>
            <td><?php echo $product['productCode']; ?></td>
            <td><?php echo $product['productName']; ?></td>
            <td class="right"><?php echo $product['listPrice']; ?></td>
            <td><form action="delete_product.php" method="post">
                  <input type="hidden" name="product_id"
                  value="<?php echo $product['productID']; ?>">
            <input type="hidden" name="category_id"
                  value="<?php echo $product['categoryID']; ?>">
            <input type="submit" value="Delete">
            </form></td>
      </tr>
      <?php endforeach; ?>
   </table>
<p><a href="add_product_form.php">Add Product</a></p>
 </section>
</main>
<footer>
    <p>&copy; <?php echo date("Y"); ?> Michael's Guitar Shop, Inc.</p>
</footer>
</body>
</html>
