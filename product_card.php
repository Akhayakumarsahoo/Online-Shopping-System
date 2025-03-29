<?php
function renderProductCard($product, $productModel) {
    $image_path = $productModel->getImagePath($product['id']);
    ?>
    <div class="col-md-3 col-6 py-2">
        <div class="card">
            <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid pb-1">
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
    <?php
} 