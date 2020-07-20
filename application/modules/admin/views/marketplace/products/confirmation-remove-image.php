<div class="row">
  	<div class="col-xl-6">
    	<form role="form" action="<?=isset($form_url) ? $form_url : '#'?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<div class="row"> 
								<div class="col-md-12">
									<?=(isset($notification) ? (!empty($notification) ? $notification : '' ) : '') ?>
								</div>     
							</div>
							<div class="row">
								<div class="col-xl-12">
									<span>Do you really want to remove this image?</span>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<br><img src="data:image/png;base64,<?=isset($base64_image) ? $base64_image : ""?>" class="img-thumbnail">
								</div>
							</div>
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<input type="hidden" name="current-datetime" value="<?=strtotime(date("YYYY-MM-dd HH:mm:ss"))?>">
										<br><button type="submit" class="btn btn-block btn-danger">REMOVE</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
  	</div>
</div>