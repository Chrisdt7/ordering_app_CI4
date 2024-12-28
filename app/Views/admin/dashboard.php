<div class="custom-layout">
	<h1><?= $title; ?></h1>
	<?php if (session()->getFlashdata('message')) : ?>
		<div class="alert alert-danger alert-message cust-flash" style="background: red; font-family: 'Poppins', sans-serif; font-size: small; color: white;">
			<?= session()->getFlashdata('message') ?>
		</div>
	<?php endif; ?>
	<?php if (session()->getFlashdata('success')) : ?>
		<div class="alert alert-success alert-message cust-flash" style="background: green; font-family: 'Poppins', sans-serif; font-size: small; color: white;">
			<?= session()->getFlashdata('success') ?>
		</div>
	<?php endif; ?>

</div>
