<?php date_default_timezone_set("Asia/Kolkata"); ?>
<header id="head" class="secondary">
	<div class="container">
	<h1>Login Peserta Ujian</h1>
	<p>Masukkan Nomor Peserta dan Kode Ujian Anda</p>
	</div>
</header>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p>
			</p>
			<form class="form-light mt-20" role="form">
				<div class="row center">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Nomor Peserta (NIM)</label>
							<input type="text" class="form-control" name="nim" id="nim" maxlength="20">
						</div>
					</div>
					<div class="col-md-4">
					</div>
				</div>
				<div class="row center">
					<div class="col-md-4">
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Kode Ujian</label>
							<input type="text" class="form-control" name="kode_ujian" id="kode_ujian" maxlength="50">
						</div>
					</div>
					<div class="col-md-4">
					</div>
				</div>
				<div align="center">
				<button type="button" class="btn btn-two" name="tbl_daftar" id="tbl_daftar">Login</button>
				<p style="margin-top:30px;">
				Pastikan anda mengisi kolom inputan (Nomor Peserta dan Kode Ujian) dengan benar, klik tombol <code>Login</code>, selanjutnya akan muncul lembar soal dan waktu pengerjaan soal otomatis mulai berjalan. Baca soal dengan baik. Jika waktu sudah berakhir, lembar soal akan ditutup dan nilai anda langsung keluar.
				</p>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal modal-info fade" id="modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Info</h4>
            </div>
            <div class="modal-body text-center">
                <h5 class="message"></h5>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-outline pull-right btn-close" data-dismiss="modal" name="btn-close">Close</button> -->
				<input type="button" class="btn btn-outline pull-right" id="btn-close" data-dismiss="modal" value="Close">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //$(document).ready(function()
	var status = 0;
	var key = "";
    jQuery(function()
    {
    	jQuery("#tbl_daftar").on("click", function()
    	{
    		var nim = jQuery("#nim").val();
    		var nama = jQuery("#nama_lengkap").val();
    		var kode = jQuery("#kode_ujian").val();
    		if(nim=="")
    		{
    			alert("Kolom NIM tidak boleh kosong");
    			jQuery("#nim").focus();
    			return false;
    		}
    		// else if(nama=="")
    		// {
    		// 	alert("Kolom Nama Lengkap tidak boleh kosong");
    		// 	jQuery("nama_lengkap").focus();
    		// 	return false;
    		// }
    		else if(kode=="")
    		{
    			alert("Kolom Kode Ujian tidak boleh kosong");
    			jQuery("#kode_ujian").focus();
    			return false;
    		}
    		else
    		{
	    		var msg = confirm("Yakin Data Anda Sudah Benar ?");
	            if (msg==true)
				{
	                jQuery.ajax (
	                {
	                    url : "<?php echo site_url('ujian/simpan_data');?>",
	                    type : "post",
	                    data : "nim="+nim+"&kode="+kode,
	                    success : function(d)
	                    {
	                    	var dt = d.split("-");
							var message = dt[0];
							var idPsrt = dt[2];
							var statusN = dt[1];
	                    	if(statusN==1)
	                    	{
	                    		// alert(dt[0]);
								status = statusN;
								key=idPsrt;
								jQuery(".modal-info .message").text(message);
								jQuery(".modal-info").modal({'show' : true});
	                    		jQuery("#kode_ujian").focus();
	                    		return false;
	                    	}
	                    	else
	                    	{
	                    		// alert(dt[0]);
								status = statusN;
								key=idPsrt;
								jQuery(".modal-info .message").text(message);
								jQuery(".modal-info").modal({'show' : true});
								// setTimeout(function(){
								// 	jQuery("#btn-close").focus();
								// }, 300);
	                    		// window.location.assign("<?php echo site_url();?>Pendaftaran/Lembar_Soal/"+dt[2]);
	                    	}
	                    }
	                });
	                return false;
	            }
	            else
	            {
	                return false;
	            }
	        }
    	});

		jQuery("#modal-info").on("hidden.bs.modal", function () {
			if (status == 2) {
				window.location.assign("<?php echo site_url();?>Ujian/Lembar_Soal/"+key);
			}
        });
		jQuery("#kode_ujian").keyup(function(e)
		{
			key = e.keyCode;
			if(key == 13){
				var nim = jQuery("#nim").val();
	    		var nama = jQuery("#nama_lengkap").val();
	    		var kode = jQuery("#kode_ujian").val();
	    		if(nim=="")
	    		{
	    			alert("Kolom NIM tidak boleh kosong");
	    			jQuery("nim").focus();
	    			return false;
	    		}
	    		// else if(nama=="")
	    		// {
	    		// 	alert("Kolom Nama Lengkap tidak boleh kosong");
	    		// 	jQuery("nama_lengkap").focus();
	    		// 	return false;
	    		// }
	    		else if(kode=="")
	    		{
	    			alert("Kolom Kode Ujian tidak boleh kosong");
	    			jQuery("kode_ujian").focus();
	    			return false;
	    		}
	    		else
	    		{
		    		var msg = confirm("Yakin Data Anda Sudah Benar ?");
		            if (msg==true)
		            {
		                jQuery.ajax (
		                {
		                    url : "<?php echo site_url('Ujian/Simpan_data');?>",
		                    type : "post",
		                    data : "nim="+nim+"&kode="+kode,
		                    success : function(d)
		                    {
		                    	var dt = d.split("-");
								var message = dt[0];
								var idPsrt = dt[2];
								var statusN = dt[1];
		                    	if(statusN==1)
		                    	{
		                    		// alert(dt[0]);
									status = statusN;
									key=idPsrt;
									jQuery(".modal-info .message").text(message);
									jQuery(".modal-info").modal({'show' : true});
		                    		jQuery("#kode_ujian").focus();
		                    		return false;
		                    	}
		                    	else
		                    	{
		                    		// alert(dt[0]);
									status = statusN;
									key=idPsrt;
									jQuery(".modal-info .message").text(message);
									jQuery(".modal-info").modal({'show' : true});
									// setTimeout(function(){
									// 	jQuery("#btn-close").focus();
									// }, 300);
		                    		// window.location.assign("<?php echo site_url();?>Pendaftaran/Lembar_Soal/"+dt[2]);
		                    	}
		                    }
		                });
		                return false;
		            }
		            else
		            {
		                return false;
		            }
		        }
			}
		})

		jQuery('#modal-info').on('show.bs.modal', function (e) {
			setTimeout(function(){
				jQuery("#btn-close").focus();
			}, 300);
		});
    });
	function tHideModal(key){

	}
    function enc(str) {
        var encoded = "";
        for (i=0; i<str.length;i++) {
            var a = str.charCodeAt(i);
            var b = a ^ 123;    // bitwise XOR with any number, e.g. 123
            encoded = encoded+String.fromCharCode(b);
        }
        return encoded;
    }
</script>
