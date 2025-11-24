<?php 

require __DIR__ . '/header.php';
require __DIR__ . '/../csrf.php';
require __DIR__ . '/db.php';

$products = [];
$searchEmpty = false;
$page = 1;
$results_per_page = 10;
$page_first_result;
$number_of_pages;

$statement = $pdo->prepare("SELECT * FROM categories ORDER BY title");
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if(!isset($_GET['p'])) {
	$page = 1;
} else {
	$page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
}

if(isset($_POST['q']) && isset($_GET['c']) && CSRF::validateToken($_POST['token'])) {
	$query = filter_input(INPUT_POST, 'q');
	$category = filter_input(INPUT_GET, 'c');
	$statement = $pdo->prepare("SELECT * FROM products WHERE category='$category' AND CONCAT(`title`, `price`, `description`, `category`) LIKE '%$query%'");
	$statement->execute();
	if($statement->rowCount() > 0){
		$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$searchEmpty = true;
	}
} elseif(isset($_POST['q']) && CSRF::validateToken($_POST['token'])) {
	$query = filter_input(INPUT_POST, 'q');
	$statement = $pdo->prepare("SELECT * FROM products WHERE CONCAT(`title`, `price`, `description`, `category`) LIKE '%$query%'");
	$statement->execute();
	if($statement->rowCount() > 0){
		$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$searchEmpty = true;
	}
} elseif(isset($_GET['c'])) {
	$page_first_result = ($page - 1) * $results_per_page;
	$statement = $pdo->prepare("SELECT count(*) FROM products WHERE category=?");
	$statement->execute(array(filter_input(INPUT_GET, 'c')));
	$number_of_result = $statement->fetchColumn();
	$number_of_pages = ceil($number_of_result / $results_per_page);

	$statement = $pdo->prepare("SELECT * FROM products WHERE category=? LIMIT $page_first_result, $results_per_page");
	$statement->execute(array(filter_input(INPUT_GET, 'c')));
	if($statement->rowCount() > 0) {
		$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$searchEmpty = true;
	}
} else {
	$page_first_result = ($page - 1) * $results_per_page;
	$statement = $pdo->prepare("SELECT count(*) FROM products");
	$statement->execute();
	$number_of_result = $statement->fetchColumn();
	$number_of_pages = ceil($number_of_result / $results_per_page);
	$statement = $pdo->prepare("SELECT * FROM products LIMIT $page_first_result, $results_per_page");
	$statement->execute();
	if($statement->rowCount() > 0) {
		$products = $statement->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$searchEmpty = true;
	}
}

$productsBaseUrl = site_url('products');
$activeCategoryParam = filter_input(INPUT_GET, 'c', FILTER_UNSAFE_RAW);
$buildPaginationUrl = function (int $pageNumber) use ($productsBaseUrl, $activeCategoryParam): string {
	$pageNumber = max(1, $pageNumber);
	$params = ['p' => $pageNumber];
	if ($activeCategoryParam !== null && $activeCategoryParam !== '') {
		$params = ['c' => $activeCategoryParam] + $params;
	}

	return $productsBaseUrl . '?' . http_build_query($params);
};


?>
<section class="products section">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="widget product-category">
					<h4 class="widget-title">Categories</h4>
					<div class="panel-group commonAccordion" id="accordion" role="tablist" aria-multiselectable="true">
					  	<div class="panel panel-default">
							<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body">
									<ul>
										<li><a href="<?= site_url('products') ?>">All</a></li>
										<?php foreach($categories as $category): ?>
											<li><a href="<?= site_url('products') ?>?c=<?= htmlspecialchars($category['title']); ?>"><?= htmlspecialchars($category['title']); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
					  	</div>
						<br>
						<?php if(isset($_GET['c'])): ?>
							<form action="<?= site_url('products') ?>?c=<?= urlencode(filter_input(INPUT_GET, 'c')) ?>" method="post">
								<?php CSRF::csrfInputField() ?>
							    <div class="form-group">
								    <input name="q" type="search" class="form-control" placeholder="Search...">
						<?php else: ?>
							<form action="<?= site_url('products') ?>" method="post">
								<?php CSRF::csrfInputField() ?>
							    <div class="form-group">
								    <input name="q" type="search" class="form-control" placeholder="Search...">
						<?php endif ?>
							    </div>
							<div class="text-center">
								<button name="search" type="submit" class="btn btn-main btn-small">Search</button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
			<div class="col-md-9">
				<div class="products-grid">
					<?php if(!$searchEmpty): ?>
						<?php foreach($products as $product): ?>
							<div class="product-grid__cell">
								<div class="product-item product-item--clickable" tabindex="0" data-product-url="<?= site_url('item') ?>?id=<?= htmlspecialchars($product['id']) ?>">
									<div class="product-thumb">
										<!--<span class="bage">Sale</span>-->
										<img class="img-responsive" src="<?= htmlspecialchars(unserialize($product['images'])[0]) ?>" alt="product-img" />
									</div>
									<div class="product-content">
										<h4><?= htmlspecialchars($product['title']) ?></h4>
										<p class="price">INR <?= number_format($product['price'], 2) ?></p>
										<form class="mt-10" method="post" action="<?= site_url('cart-add-item') ?>">
											<?php CSRF::csrfInputField() ?>
											<input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
										    <input type="hidden" name="quantity" value="1">
										    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? site_url('products')) ?>">
											<button type="submit" class="btn btn-main btn-small btn-block">
												<i class="tf-ion-android-cart"></i> Add to Cart
											</button>
										</form>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="col-md-6 col-md-offset-3">
							<div class="block text-center">
								<i class="tf-ion-ios-cart-outline"></i>
								<h2 class="text-center">No items found.</h2>
								<a href="<?= site_url('products') ?>" class="btn btn-main mt-20">Return to shop</a>
							</div>
						</div>
					<?php endif ?>

		

				</div>				
			</div>
		
		</div>
		<?php if(!isset($_POST['q']) && isset($number_of_pages) && $number_of_pages > 1): ?>
			<div class="row">
				<div class="col-sm-12 text-center">
					<?php
						$maxLinks = 3;
						$startPage = max(1, (int)$page - 1);
						$endPage = min($number_of_pages, $startPage + $maxLinks - 1);
						$startPage = max(1, $endPage - $maxLinks + 1);

						for ($i = $startPage; $i <= $endPage; $i++) {
							echo '<a href="' . htmlspecialchars($buildPaginationUrl($i)) . '">' . $i . '</a>';
						}
					?>
				</div>
			</div>
		<?php endif ?>
	</div>
</section>
<?php require __DIR__ . '/footer.php'; ?>