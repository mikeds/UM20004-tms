		<!-- content-wrapper ends -->
		</div>
		<!-- page-body-wrapper ends -->
	</div>
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