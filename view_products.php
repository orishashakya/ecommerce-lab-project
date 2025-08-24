<?php 
include('config/constants.php'); 
?>

<h2>Product List by Category</h2>

<?php
// Function to display products by category
function displayProductsByCategory($conn) {
    // First, get all main categories (parent categories)
    $main_categories_sql = "SELECT * FROM categories WHERE parent_category_id IS NULL";
    $main_categories_result = mysqli_query($conn, $main_categories_sql);
    
    if (!$main_categories_result) {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
        return;
    }
    
    while ($main_category = mysqli_fetch_assoc($main_categories_result)) {
        echo "<h3>" . $main_category['category_name'] . "</h3>";
        
        // Get all subcategories (brands) for this main category
        $subcategories_sql = "SELECT * FROM categories WHERE parent_category_id = " . $main_category['category_id'];
        $subcategories_result = mysqli_query($conn, $subcategories_sql);
        
        if ($subcategories_result && mysqli_num_rows($subcategories_result) > 0) {
            while ($subcategory = mysqli_fetch_assoc($subcategories_result)) {
                echo "<h4>" . $subcategory['category_name'] . " Products:</h4>";
                
                // Get all products for this subcategory (brand)
                $products_sql = "SELECT * FROM products WHERE category_id = " . $subcategory['category_id'] . " AND is_active = 'Yes' ORDER BY product_name";
                $products_result = mysqli_query($conn, $products_sql);
                
                if ($products_result && mysqli_num_rows($products_result) > 0) {
                    echo "<ul>";
                    while ($product = mysqli_fetch_assoc($products_result)) {
                        echo "<li>";
                        echo "<strong>" . $product['product_name'] . "</strong>";
                        echo " - $" . number_format($product['price'], 2);
                        if (!empty($product['description'])) {
                            echo " - " . substr($product['description'], 0, 50) . "...";
                        }
                        echo "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No products found for " . $subcategory['category_name'] . "</p>";
                }
            }
        } else {
            echo "<p>No subcategories found for " . $main_category['category_name'] . "</p>";
        }
        echo "<hr>";
    }
}

// Call the function to display products
displayProductsByCategory($conn);

// Close connection
mysqli_close($conn);
?>