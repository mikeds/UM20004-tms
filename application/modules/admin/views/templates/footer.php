		</div>
        <!-- content-wrapper ends -->
		<!-- partial:partials/_footer.html -->
		<footer class="footer">
			<div class="d-sm-flex justify-content-center justify-content-sm-between">
			<span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2020 <a href="#" target="_blank">BambuPAY</a>. All rights reserved.</span>
			<!-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span> -->
			</div>
		</footer>
		<!-- partial -->
		</div>
		<!-- main-panel ends -->
	</div>
	<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
	<script>
		var base_url = '<?=base_url()?>';
	</script>
	<?php
		foreach ($javascripts as $javascript ) {
			echo '<script src="'.$javascript.'"></script>';
		}
	?>
	</body>
</html>