<div class="row"> 
	<div class="col-md-12">
		<?=(isset($notification) ? (!empty($notification) ? $notification : '' ) : '') ?>
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