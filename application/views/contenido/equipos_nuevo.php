<script type="text/javascript" src="<?=base_url()?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#form input[type=text]').blur(function(){
			$(this).val($.trim($(this).val()));
			if($(this).attr("name")=="url")
				$(this).valid();
		});
		$('#form').validate({
			submitHandler: envioForm,
			rules:{
				descripcion:{
					maxlength: 1000
				},
				imagen:{
					required: true,
					accept: "jpg|png|gif"
				}
			}
		});
	});
	function envioForm(form)
	{
		$(form).find("input[type=submit]").hide();
		$(form).find("input[type=reset]").hide();
		$(form).find("#formLoader").show();
		form.submit();
	}
</script>
<?php echo validation_errors('<div class="msgError">','</div>');?>
<form name="form" id="form" action="<?=current_url()?>" method="post">
    <div class="input-group">
      <span class="input-group-addon">Nombre del Equipo</span>
      <input type="text" name="name" class="form-control" placeholder="Equipo" value="<?=set_value("name")?>">
    </div>
    <input type="hidden" name="postback" value="1"/>
    <br/>
    <div class="input-group">
      <span class="input-group-addon">Miembro #1</span>
      <input type="text" name="member_1" class="form-control" placeholder="Miembro #1" value="<?=set_value("member_1")?>">
    </div>
    <br/>
    <div class="input-group">
      <span class="input-group-addon">Miembro #2</span>
      <input type="text" name="member_2" class="form-control" placeholder="Miembro #2" value="<?=set_value("member_2")?>">
    </div>
    <br/>
    <div class="btn-group" style="text-align: center">
      <input type="submit" class="btn btn-default" value="Registrar"/>
      <input type="reset" class="btn btn-default" value="Limpiar"/>
    </div>
</form>