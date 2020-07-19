<div class="row">

  	<div class="col-xl-8">
    	<form role="form" action="<?=isset($form_url) ? $form_url : '#'?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-header">
							<?=isset($title) ? $title : ""?>
						</div>
						<div class="card-body">
							<div class="row"> 
								<div class="col-md-12">
									<?=(isset($notification) ? (!empty($notification) ? $notification : '' ) : '') ?>
								</div>     
							</div>
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<?=isset($category) ? $category : ""?>
										<span class="text-danger"><?=form_error('category')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label>Product Name <span class="text-danger">*</span></label>
										<input name="product-name" class="form-control" placeholder="Product Name" value="<?=isset($post['product-name']) ? $post['product-name'] : ""?>">
										<span class="text-danger"><?=form_error('product-name')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label>Product Description <span class="text-danger">*</span></label>
										<textarea class="form-control" name="product-description" rows="10"><?=isset($post['product-description']) ? $post['product-description'] : ""?></textarea>
										<span class="text-danger"><?=form_error('product-description')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label>Image Upload<span class="text-danger">*</span></label>
										<input class="form-control" name="images[]" type="file" multiple="multiple" accept="image/x-png,image/jpeg">
										<span class="text-danger"><?=form_error('images')?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<button type="submit" class="btn btn-block btn-success">SAVE</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
  	</div>

	<div class="col-xl-4">
		<div class="row">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header">
						Image Gallery
					</div>
					<div class="card-body">
						<?=isset($image_gallery) ? $image_gallery : ""?>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>