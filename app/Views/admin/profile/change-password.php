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
	
	<form action="<?= base_url('changepassword'); ?>" method="POST">
        <?= csrf_field() ?>
        <div class="form-group mt-1 mb-1">
			<label for="current-password" class="cust-label">Current Password:</label>
			<input type="password" id="current-password" name="current-password" class="form-control cust-control" placeholder="Your Current Password" autofocus>
			<?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['current-password'])): ?>
				<div class="text-danger text-center small">
					<?= session()->getFlashdata('validation')['current-password'] ?>
				</div>
			<?php endif; ?>
		</div>
        <div class="form-group mt-1 mb-1">
			<label for="new-password" class="cust-label">New Password:</label>
			<input type="password" id="new-password" name="new-password" class="form-control cust-control" placeholder="Your New Password" autofocus>
			<?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['new-password'])): ?>
				<div class="text-danger text-center small">
					<?= session()->getFlashdata('validation')['new-password'] ?>
				</div>
			<?php endif; ?>
		</div>
        <div class="form-group mt-1 mb-1">
			<label for="confirm-password" class="cust-label">Confirm Password:</label>
			<input type="password" id="confirm-password" name="confirm-password" class="form-control cust-control" placeholder="Confirm Your Password" autofocus>
			<?php if (session()->getFlashdata('validation') && isset(session()->getFlashdata('validation')['confirm-password'])): ?>
				<div class="text-danger text-center small">
					<?= session()->getFlashdata('validation')['confirm-password'] ?>
				</div>
			<?php endif; ?>
		</div>
        <button type="submit">Change Password</button>
    </form>

</div>