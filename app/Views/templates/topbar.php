<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-flex align-items-center fixed-top">
	<div class="container d-flex justify-content-center justify-content-md-between">
		<div class="contact-info d-flex align-items-center">
			<i class="bi bi-phone d-flex align-items-center"><span>+62 911-832-1222</span></i>
			<i class="bi bi-clock d-flex align-items-center ms-4"><span> Mon-Sat: 10AM - 22PM</span></i>
		</div>
		<div class="languages d-none d-md-flex align-items-center">
			<ul>
				<li><a href="#" class="nav-link">Ind</a></li>
				<li style="color: #1707f2; text-shadow: 1px 1px 5px #85adff;">En</li>
			</ul>
		</div>
	</div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center">
	<div class="container-fluid container-xl d-flex align-items-center justify-content-lg-between">

		<h1 class="logo me-auto me-lg-0"><a href="<?= base_url('/')?>">Digi Resto</a></h1>
		<!-- Uncomment below if you prefer to use an image logo -->
		<!-- <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

		<nav id="navbar" class="navbar order-last order-lg-0">
			<ul>
				<li><a class="nav-link active" href="<?= base_url('/')?>">Home</a></li>
				<li><a class="nav-link" href="#about">About</a></li>
				<li><a class="nav-link" href="#gallery">Gallery</a></li>
				<li class="dropdown cust-dropdown"><a href="#"><span>Menu</span> <i class="bi bi-chevron-down"></i></a>
					<ul>
						<li class="dropdown"><a href="#"><span>Digi Food</span> <i class="bi bi-chevron-right"></i></a>
							<ul>
							<li><a href="#">Deep Drop Down 1</a></li>
							<li><a href="#">Deep Drop Down 2</a></li>
							<li><a href="#">Deep Drop Down 3</a></li>
							<li><a href="#">Deep Drop Down 4</a></li>
							<li><a href="#">Deep Drop Down 5</a></li>
							</ul>
						</li>
						<li class="dropdown"><a href="#"><span>Digi Drinks</span> <i class="bi bi-chevron-right"></i></a>
							<ul>
							<li><a href="#">Deep Drop Down 1</a></li>
							<li><a href="#">Deep Drop Down 2</a></li>
							<li><a href="#">Deep Drop Down 3</a></li>
							<li><a href="#">Deep Drop Down 4</a></li>
							<li><a href="#">Deep Drop Down 5</a></li>
							</ul>
						</li>
						<li><a href="#">Digi Dessert</a></li>
					</ul>
				</li>
				<li><a class="nav-link scrollto" href="#contact">Contact</a></li>
			</ul>
			<i class="bi bi-list mobile-nav-toggle"></i>
		</nav><!-- .navbar -->
		<?php if (!empty(session('id_users'))) { ?>
			<div class="profile">
				<a class="nav-link" style="margin: 0 0 0 15px; padding: 8px 25px;" href="<?= base_url('order') ?>"><b><?= $numRows; ?></b> Order</a>
				<div style="margin: 0 0 0 15px; padding: 8px 25px;">Welcome back <?php echo session()->get('username'); ?></div>
				<a class="nav-link book-a-table-btn cust-btn" href="<?= base_url('user/profile'); ?>">Profil Saya</a>
				<a href="<?= base_url('logout') ?>">Logout</a>
			</div>
		<?php } else { ?>
			<div class="d-flex align-items-center justify-content-around">
				<button class="book-a-table-btn cust-btn" onclick="openDrawer('loginDrawer')">Login</button>
				<button class="book-a-table-btn cust-btn" onclick="openDrawer('registerDrawer')">Register</button>
			</div>
		<?php } ?>

	</div>
</header><!-- End Header -->