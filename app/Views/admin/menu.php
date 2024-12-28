<div class="custom-layout flex-column">
	<h1><?= $title; ?></h1>
	<h3><?= $subtitle; ?></h3>

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
					<th scope="col">Name</th>
					<th scope="col">Desc</th>
					<th scope="col">Price</th>
					<th scope="col">Image</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>

			<tbody>
				<?php if (!empty($items)) : ?>
				<?php $a = 1; foreach ($items as $item) : ?>
				<tr style="font-size: 12px !important;">
					<th scope="row"><?= $a++; ?></th>
					<td scope="col"><?= $item['menu_name'] ?></td>
					<td scope="col"><?= $item['description'] ?></td>
					<td scope="col"><?= $item['price'] ?></td>
					<td>
					<?php $imgUrl = base_url('assets/img/menu/' . $item['image']); ?>
						<picture>
							<img src="<?= $imgUrl; ?>" class="img-thumbnail" alt="..." style="width: 150px; height: 100px;">
						</picture>
					</td>
					<td>
						<a href="#updateModal<?= $item['id_menu'] ?>" class="badge badge-info" data-toggle="modal">
							<i class="fas fa-edit"></i> Ubah
						</a>
						<a href="<?= base_url('delete-menu/' . $item['id_menu']); ?>" onclick="return confirm('Are you sure want to delete <?= $title . ' ' . $item['menu_name']; ?> ?');" class="badge badge-danger">
							<i class="fas fa-trash"></i> Hapus
						</a>
					</td>
				</tr>
				<!------------ Update Modal ------------>
                <div class="modal cust-modal fade" id="updateModal<?= $item['id_menu'] ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog cust-modal-dialog">
                        <div class="modal-content cust-modal-content">
                            <div class="modal-header cust-modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body cust-modal-body">
                                <form action="<?= base_url('update-menu/' . $item['id_menu']) ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="form-group cust-form-group">
                                        <label for="menu_name" class="cust-label">Menu Name</label>
                                        <input type="text" class="form-control cust-form-control" id="menu_name" name="menu_name" value="<?= $item['menu_name'] ?>" required>
                                    </div>
                                    <div class="form-group cust-form-group">
                                        <label for="description" class="cust-label">Description</label>
                                        <textarea class="form-control cust-form-control" id="description" name="description" required><?= $item['description'] ?></textarea>
                                    </div>
                                    <div class="form-group cust-form-group">
                                        <label for="category" class="cust-label">Category</label>
                                        <div class="custom-select-wrapper">
                                            <select class="form-control cust-form-control" id="category" name="category" required>
                                                <?php foreach ($categories as $cat) : ?>
                                                    <option value="<?= $cat['id_category']; ?>" <?= $cat['id_category'] == $item['id_category'] ? 'selected' : '' ?>><?= $cat['category_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="bi bi-caret-down-fill" id="caretIcon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group cust-form-group">
                                        <label for="price" class="cust-label">Price</label>
                                        <div class="form-control cust-form-control">
                                            <div class="form-currency">
                                                <label for="price" class="cust-label" id="price-label">Rp.</label>
                                                <input type="number" class="form-control cust-form-control" id="price" name="price" step="1000" value="<?= $item['price'] ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group cust-form-group">
                                        <label for="image" class="cust-label">Image</label>
                                        <input type="file" class="form-control cust-form-control" id="image" name="image">
                                        <input type="hidden" name="old_image" value="<?= $item['image'] ?>">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="submit" class="cust-btn1 cust-label" style="width: 100px;">Update Menu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7" style="text-align: center;">No menu available.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!------------ Add Modal ------------>
	<div class="modal cust-modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog cust-modal-dialog">
			<div class="modal-content cust-modal-content">
				<div class="modal-header cust-modal-header">
					<h5 class="modal-title" id="updateModalLabel">Add New Menu</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body cust-modal-body">
					<form action="<?= base_url('add-menu')?>" method="post" enctype="multipart/form-data">
						<?= csrf_field() ?>
						<div class="form-group cust-form-group">
							<label for="menu_name" class="cust-label">Menu Name</label>
							<input type="text" class="form-control cust-form-control" id="menu_name" name="menu_name" required>
						</div>
						<div class="form-group cust-form-group">
							<label for="description" class="cust-label">Description</label>
							<textarea class="form-control cust-form-control" id="description" name="description" required></textarea>
						</div>
						<div class="form-group cust-form-group">
							<label for="category" class="cust-label">Category</label>
							<div class="custom-select-wrapper">
								<select class="form-control cust-form-control" id="category" name="category" required>
									<?php foreach ($categories as $cat) : ?>
										<option value="<?= $cat['id_category']; ?>"><?= $cat['category_name']; ?></option>
									<?php endforeach; ?>
								</select>
								<i class="bi bi-caret-down-fill" id="caretIcon"></i>
							</div>
						</div>
						<div class="form-group cust-form-group">
							<label for="price" class="cust-label">Price</label>
							<div class="form-control cust-form-control">
								<div class="form-currency">
									<label for="price" class="cust-label" id="price-label">Rp.</label>
									<input type="number" class="form-control cust-form-control" id="price" name="price" step="1000" required>
								</div>
							</div>
						</div>
						<div class="form-group cust-form-group">
							<label for="image" class="cust-label">Image</label>
							<input type="file" class="form-control cust-form-control" id="image" name="image" required>
						</div>
						<div class="d-flex align-items-center justify-content-center">
							<button type="submit" class="cust-btn1 cust-label" style="width: 100px;">Add Menu</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!------------ End Add Modal ------------>
</div>