<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Results | Planet Shopify</title>
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
    include 'includes/common.php';
    
    $search = isset($_GET['q']) ? $_GET['q'] : '';
    $search = mysqli_real_escape_string($con, $search);
    
    // Search in products table
    $query = "SELECT * FROM products WHERE name LIKE ? OR price LIKE ?";
    $search_term = "%$search%";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    
    <div class="container" style="margin-top:65px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Search Results</li>
            </ol>
        </nav>
        
        <h2 class="mb-4">Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>
        
        <?php if ($result->num_rows === 0): ?>
            <div class="alert alert-info">
                No products found matching your search.
            </div>
        <?php else: ?>
            <div class="row text-center">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="col-md-3 col-6 py-2">
                        <div class="card">
                            <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                                <?php
                                $category = '';
                                if ($product['id'] <= 4) $category = 'watch';
                                else if ($product['id'] <= 8) $category = 'shirt';
                                else if ($product['id'] <= 12) $category = 'shoe';
                                else $category = 'sp';
                                $image_num = $product['id'] % 4 == 0 ? 4 : $product['id'] % 4;
                                ?>
                                <img src="images/<?php echo $category . $image_num; ?>.jpg" alt="" class="img-fluid pb-1">
                                <div class="figure-caption">
                                    <h6><?php echo htmlspecialchars($product['name']); ?></h6>
                                    <h6>Price: Rs <?php echo htmlspecialchars($product['price']); ?></h6>
                                </div>
                            </a>
                            <?php if (!isset($_SESSION['email'])) { ?>
                                <p><a href="index.php#login" role="button" class="btn btn-warning text-white">Add To Cart</a></p>
                            <?php } else {
                                if (check_if_added_to_cart($product['id'])) {
                                    echo '<p><a href="#" class="btn btn-warning text-white" disabled>Added to cart</a></p>';
                                } else { ?>
                                    <p><a href="cart-add.php?id=<?php echo $product['id']; ?>" name="add" value="add" class="btn btn-warning text-white">Add to cart</a></p>
                                <?php }
                            } ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html> 