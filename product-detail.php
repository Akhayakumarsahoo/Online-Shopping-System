<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Details | Planet Shopify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Delius Swash Caps' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Andika' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    include 'includes/header_menu.php';
    include 'includes/check-if-added.php';
    
    // Get product ID from URL
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Connect to database
    include 'includes/common.php';
    
    // Fetch product details
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: products.php");
        exit();
    }
    
    $product = $result->fetch_assoc();
    ?>
    
    <div class="container product-detail" style="margin-top:65px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
        
        <div class="row">
            <div class="col-md-6">
                <img src="images/<?php 
                    $category = '';
                    if ($product_id <= 4) $category = 'watch';
                    else if ($product_id <= 8) $category = 'shirt';
                    else if ($product_id <= 12) $category = 'shoe';
                    else $category = 'sp';
                    echo $category . ($product_id % 4 == 0 ? '4' : $product_id % 4) . '.jpg';
                ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="lead">Price: Rs <?php echo htmlspecialchars($product['price']); ?></p>
                
                <?php if (!isset($_SESSION['email'])) { ?>
                    <p><a href="index.php#login" role="button" class="btn btn-warning text-white">Add To Cart</a></p>
                <?php } else {
                    if (check_if_added_to_cart($product_id)) {
                        echo '<p><a href="#" class="btn btn-warning text-white" disabled>Added to cart</a></p>';
                    } else {
                ?>
                        <p><a href="cart-add.php?id=<?php echo $product_id; ?>" name="add" value="add" class="btn btn-warning text-white">Add to cart</a></p>
                <?php
                    }
                } ?>
                
                <div class="details-section mt-4">
                    <h3>Product Details</h3>
                    <p>Experience premium quality and style with the <?php echo htmlspecialchars($product['name']); ?>. 
                    This product combines elegant design with superior craftsmanship to deliver exceptional value.</p>
                    
                    <h4 class="mt-4">Key Features:</h4>
                    <ul>
                        <li>Premium quality materials</li>
                        <li>Elegant design</li>
                        <li>Comfortable fit</li>
                        <li>Durable construction</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html> 