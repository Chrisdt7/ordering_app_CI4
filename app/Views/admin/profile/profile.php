<div class="custom-layout flex-column">
	<h1><?= $title; ?></h1>
	
	<?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-danger alert-message cust-flash" style="background: red; font-family: 'Poppins', sans-serif; font-size: small;">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-message cust-flash" style="background: green; font-family: 'Poppins', sans-serif; font-size: small;">
			<?= session()->getFlashdata('success') ?>
		</div>
    <?php endif; ?>

	<div class="profile">
		<div class="profile-content">
			<div class="profile-image">
				<img src="<?= base_url('assets/img/avatar/') . $user['image']; ?>" alt="default.png" style="width: 150px; height: auto; border-radius: 50px;">
				<div class="cust-text mb-2"><?= $user['role']; ?></div>
			</div>
			<div class="profile-items">
				<div class="cust-text text-left">Username</div>
				<div class="cust-text text-center">:</div>
				<div class="cust-text text-end"><?= $user['username']; ?></div>
			</div>
			<div class="profile-items">
				<div class="cust-text text-left">Full Name</div>
				<div class="cust-text text-center">:</div>
				<div class="cust-text text-end"><?= $user['name']; ?></div>
			</div>
			<div class="profile-items">
				<div class="cust-text text-left">E-Mail</div>
				<div class="cust-text text-center">:</div>
				<div class="cust-text text-end"><?= $user['email']; ?></div>
			</div>
			<div class="profile-items">
				<div class="cust-text text-left">Contact</div>
				<div class="cust-text text-center">:</div>
				<div class="cust-text text-end"><?= $user['contact']; ?></div>
			</div>

			<a href="<?= base_url('profile/update') ?>" class="cust-button mt-3 mb-3">Update Profile</a>
		</div>
	</div>
</div>