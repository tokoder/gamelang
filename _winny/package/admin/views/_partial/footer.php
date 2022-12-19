<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */
?>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
                <p>
                    <b>We'll let you know when it's done</b>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalgantipass" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Ganti Password</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					if($this->setting->demo() == true){
						echo "Fitur tidak tersedia di mode demo aplikasi";
					}else{
						echo 
						form_open('', 'id="userpass"').'
							<div class="form-group">
								<label>Password Baru</label>
								<input type="password" id="usrpass" name="gantipass" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Ulangi Password</label>
								<input type="password" id="usrpass2" class="form-control" required />
							</div>
							<div class="form-group m-tb-10">
								<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
							</div>
						</form>';
					}
				?>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="names" value="<?=$this->security->get_csrf_token_name()?>" />
<input type="hidden" id="tokens" value="<?=$this->security->get_csrf_hash();?>" />
