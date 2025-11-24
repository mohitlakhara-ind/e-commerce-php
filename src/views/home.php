<?php 

require __DIR__ . '/header.php'; 
require __DIR__ . '/db.php';
require __DIR__ . '/../csrf.php';

$items = [];
$statement = $pdo->prepare("SELECT * FROM products ORDER BY rand() LIMIT 9");
$statement->execute();
if ($statement->rowCount() > 0) {
    $items = $statement->fetchAll(PDO::FETCH_ASSOC);
}

$itemsPerRow = 4;
$itemCount = count($items);
$placeholdersNeeded = $itemCount % $itemsPerRow === 0 ? 0 : $itemsPerRow - ($itemCount % $itemsPerRow);

?>
<section class="products section bg-gray">
	<div class="container">
		<div class="row">
			<div class="title text-center">
				<h2>Fresh drops picked for you</h2>
			</div>
		</div>
		<div class="products-grid">
			<?php foreach($items as $item): ?>
				<div class="col-md-4 product-grid__cell">
					<div class="product-item product-item--clickable" tabindex="0" data-product-url="<?= site_url('item') ?>?id=<?= htmlspecialchars($item['id']) ?>">
						<div class="product-thumb">
							<img class="img-responsive" src="<?= htmlspecialchars(unserialize($item['images'])[0]) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
						</div>
						<div class="product-content">
							<h4><?= htmlspecialchars($item['title']) ?></h4>
							<p class="price">INR <?= number_format($item['price'], 2) ?></p>
							<form class="mt-10" method="post" action="<?= site_url('cart-add-item') ?>">
								<?php CSRF::csrfInputField() ?>
								<input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
								<input type="hidden" name="quantity" value="1">
								<input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? site_url()) ?>">
								<button type="submit" class="btn btn-main btn-small btn-block">
									<i class="tf-ion-android-cart"></i> Add to Cart
								</button>
							</form>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<?php for($i = 0; $i < $placeholdersNeeded; $i++): ?>
				<div class="col-md-4 product-grid__cell product-grid__cell--placeholder">
					<div class="product-item product-item--placeholder">
						<div class="product-thumb skeleton-block"></div>
						<div class="product-content">
							<p class="placeholder-title">New picks curated daily</p>
							<p class="placeholder-copy">More items are on the way.</p>
							<div class="skeleton-button"></div>
						</div>
					</div>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>


<!--
Start Call To Action
==================================== -->
<section class="call-to-action bg-gray section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="title">
					<h2>Stay in the NovaMart loop</h2>
					<p>Weekly stock alerts, seasonal recipes, and insider perks. Zero spam, zero fluff.</p>
				</div>
				<div class="col-lg-6 col-md-offset-3">
				    <div class="input-group subscription-form">
				      <input type="text" class="form-control" placeholder="you@email.com">
				      <span class="input-group-btn">
				        <button class="btn btn-main" type="button">Send me updates</button>
				      </span>
				    </div><!-- /input-group -->
			  </div><!-- /.col-lg-6 -->

			</div>
		</div> 		<!-- End row -->
	</div>   	<!-- End container -->
</section>   <!-- End section -->

<?php require __DIR__ . '/footer.php'; ?>