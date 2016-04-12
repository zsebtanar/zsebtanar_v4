<div id="login" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Admin</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<form role="form">
							<div class="form-group">
								<label for="pwd">Jelszó:</label>
								<input name="password" type="password" class="form-control" id="pwd">
							</div>
							<p class="text-center" id="login_message"></p>
							<div class="text-right">
								<button type="submit" class="btn btn-default" onclick=login(event)>Mehet</button>
							</div>
						</form>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    function login(event) {
    	event.preventDefault();
    	var pwd = document.getElementById('pwd').value;
        window.location.assign("<?php echo base_url();?>action/login/"+pwd);
    }
</script>