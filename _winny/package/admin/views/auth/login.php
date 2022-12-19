<div class="wrap">
	<div class="login-head" style="background-color: #1da1f2">
		<b>MASUK</b>
	</div>
	<div class="login">
		<?=form_open('', 'id="login"');?>
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" class="form-control" id="username" name="username" required />
			</div>
			<div class="form-group">
				<label for="pass">Password</label>
				<input type="password" class="form-control" id="pass" name="pass" required />
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success"><i class="la la-check"></i> Masuk</button>
			</div>
			<input type="hidden" class="tokens" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>" />
		</form>
	</div>
</div>
<div class="login-footer">
	Copyrights &copy; <?php echo date("Y"); ?> | <?=$this->setting->globalset("nama")?>
</div>