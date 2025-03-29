<?php
session_start();
require_once 'includes/common.php';
require_once 'includes/check-if-added.php';
require_once 'src/models/Product.php';
require_once 'src/views/products/product_card.php';

// Initialize Product model
$productModel = new Product($con);

// Define product categories
$categories = [
    'watch' => 'Watches',
    'shirt' => 'T-Shirts',
    'shoes' => 'Shoes',
    'headphones' => 'Headphones & Speakers'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Planet Shopify | Online Shopping Site for Men</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Delius Swash Caps' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Andika' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header_menu.php'; ?>

    <div class="container" style="margin-top:65px">
        <!-- Jumbotron -->
        <div class="jumbotron text-center">
            <h1>Welcome to Planet Shopify!</h1>
            <p>We have wide range of products for you. No need to hunt around, we have all in one place</p>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>

        <!-- Category Navigation -->
        <div class="category-nav mb-4">
            <div class="row">
                <?php foreach ($categories as $id => $name): ?>
                    <div class="col-md-3 col-6">
                        <a href="#<?php echo $id; ?>" class="btn btn-outline-warning btn-block">
                            <?php echo htmlspecialchars($name); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Product Categories -->
        <?php foreach ($categories as $id => $name): ?>
            <section id="<?php echo $id; ?>" class="product-section mb-5">
                <h2 class="text-center mb-4"><?php echo htmlspecialchars($name); ?></h2>
                <div class="row">
                    <?php
                    $products = $productModel->getProductsByCategory($id);
                    foreach ($products as $product) {
                        renderProductCard($product, $productModel);
                    }
                    ?>
                </div>
            </section>
            <?php if ($id !== array_key_last($categories)): ?>
                <hr class="my-5">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function(){
        // Initialize popovers
        $('[data-toggle="popover"]').popover();
        
        // Smooth scroll for category navigation
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if(target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 500);
            }
        });

        // Handle URL hash for direct category access
        if(window.location.hash) {
            const target = $(window.location.hash);
            if(target.length) {
                setTimeout(function() {
                    window.scrollTo(0, target.offset().top - 80);
                }, 1);
            }
        }
    });

    <?php if (isset($_GET['error'])): ?>
        $(document).ready(function(){
            $('#signup').modal('show');
            alert(<?php echo json_encode($_GET['error']); ?>);
        });
    <?php endif; ?>

    <?php if (isset($_GET['errorl'])): ?>
        $(document).ready(function(){
            $('#login').modal('show');
            alert(<?php echo json_encode($_GET['errorl']); ?>);
        });
    <?php endif; ?>
    </script>
</body>
</html>