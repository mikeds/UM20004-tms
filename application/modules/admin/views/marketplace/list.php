<div class="row"> 
	<div class="col-md-12">
		<?=(isset($notification) ? (!empty($notification) ? $notification : '' ) : '') ?>
	</div>
	<div class="col-md-2"><br/>
		<a href="<?=(isset($add_url) ? (!empty($add_url) ? $add_url : '' ) : '')?>" class="btn btn-block btn-primary">
			<i class="fa fa-plus"></i>
		<?=(isset($add_label) ? (!empty($add_label) ? $add_label : '' ) : '')?></a>
	</div>
</div>
<div class="row"> 
	<div class="col-md-12"><br/>
		<div class="card">
			<div class="card-header">
				<?=isset($title) ? $title : ""?>
			</div>
			<div class="card-body">
				<?php
					if(isset($listing)){
						foreach ($listing as $list) {
							echo $list;
						}
					}
				?>
			</div>
		</div>
	</div>
</div>