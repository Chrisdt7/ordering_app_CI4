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

	<div class="w-100 d-flex align-items-center justify-content-center">
		<a href="#addModal" class="cust-btn-1" data-toggle="modal">
			<span class="icon">
				<i class="fas fa-plus"></i>
			</span>
			<span class="cust-text">
				Add
			</span>
		</a>
	</div>

	<div class="content-layout">
		<table class="table-layout">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Table Number</th>
					<th scope="col">QR Code</th>
				</tr>
			</thead>

			<tbody>
				<?php if (!empty($tables)) : ?>
				<?php $a = 1; foreach ($tables as $tb) : ?>
				<tr style="font-size: 12px !important;">
					<th scope="row"><?= $a++; ?></th>
					<td scope="col"><?= $tb['table_number'] ?></td>
					<td scope="col">
						<?php if ($tb['qr_code']) : ?>
							<img src="<?= base_url($tb['qr_code']); ?>" alt="QR Code">
						<?php else : ?>
							N/A
						<?php endif; ?>
					</td>
					<td>
						<a href="<?= base_url('menu/ubahTable/' . $tb['table_number']); ?>" class="badge badge-info">
							<i class="fas fa-edit"></i> Ubah
						</a>
						<a href="<?= base_url('delete-table/' . $tb['table_number']); ?>" onclick="return confirm('Kamu yakin akan menghapus <?= $title . ' ' . $tb['table_number']; ?> ?');" class="badge badge-danger">
							<i class="fas fa-trash"></i> Hapus
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7" style="text-align: center;">No table available.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!------------ Modal ------------>
	<div class="modal cust-modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog cust-modal-dialog">
			<div class="modal-content cust-modal-content">
				<div class="modal-header cust-modal-header">
					<h5 class="modal-title" id="addModalLabel">Add New Table</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body cust-modal-body">
					<form action="<?= base_url('add-table')?>" method="post" enctype="multipart/form-data">
						<?= csrf_field() ?>
						<div class="form-group">
                        <label for="num_tables">Number of Tables to Add:</label>
                        <select class="form-control" id="num_tables" name="num_tables">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
						<div class="d-flex align-items-center justify-content-center">
							<button type="submit" class="cust-btn1 cust-label" style="width: 100px;">Add Table</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>