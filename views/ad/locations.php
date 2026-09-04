<?php $this->dispatch("layout/header_iframe");?>

<script type="text/javascript">
function addOption(text,value)
{
	var optn = document.createElement("option");
	optn.text = text;
	optn.value = value;
	document.getElementById('loc').options.add(optn);
}

function addCountry(text,value)
{
	var optn1 = document.createElement("option");
	optn1.text = text;
	optn1.value = value;
	document.getElementById('countries').options.add(optn1);
}

function addOption_list()
{
	var loctn=document.loca.countries.options;
	for(i=loctn.length-1;i>=0;i--)
	{
	if(loctn[i].selected)
	    {
		addOption( loctn[i].text, loctn[i].value);
		document.loca.countries.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.loca.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.loca.loc.options[j].value+",");
	}
	sortSelect(document.loca.loc);
}

function deleteOption_list()
{
	var delt=document.loca.loc.options;
	for(i=delt.length-1;i>=0;i--)
	{
	if(delt[i].selected)
		{
		addCountry(delt[i].text, delt[i].value);
		document.loca.loc.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.loca.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.loca.loc.options[j].value+",");
	}
	sortSelect(document.loca.countries);
}
</script>

<?php
$result  = $this->get_result('result');
$message = $this->get_variable('message');

$aid=$this->get_variable('aid');
$tar_count=$this->get_variable('tar_count');

$form=$this->create_form();
$form->start("loca",$this->make_url("ad/locations/").$aid,"post");

$result3=$this->get_result('result3');
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 location-targeting">

<h2 class="section-heading"><?php echo $this->get_label('targeted locations');?></h2>

	<div class="row">

		<?php if($tar_count == 0){?>
		<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message"><?php echo $this->get_label('no location');?></div>
		<?php }else if($message != ''){?>
		<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message"><?php echo $message;?></div>
		<?php }?>

		<div class="col-md-5 col-sm-5 col-xs-5">
			<div class="form-group">
		    <select class="form-select country_list" multiple name="countries" id="countries">
		        <?php foreach($result as $key=>$value){?>
		          <option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
		        <?php }?>
		    </select>
			</div>
		</div>

		<div class="col-md-2 col-sm-2 col-xs-2 row d-flex align-items-center">
					<div onClick="addOption_list()" class="d-flex align-items-center justify-content-center location-add">
						<i class="fa fa-arrow-circle-right"></i>
					</div>
					<div onClick="deleteOption_list()" class="d-flex align-items-center justify-content-center location-remove">
						<i class="fa fa-arrow-circle-left"></i>
					</div>
		</div>

		<div class="col-md-5 col-sm-5 col-xs-5">
			<div class="form-group">
			    <select class="form-select country_list" multiple id="loc" name="loc">
			      	<?php
				        $code_str = "";
				        foreach($result3 as $key=>$val)
								{
				        		$code_str.=     $val['country_code'].","	;
				            $country_name = $this->get_country_name($val['country_code']);

				            if($country_name != ""){?>
				              <option value="<?php echo $val['country_code'];?>"><?php echo $country_name;?></option>
				       		<?php }
				        }
							?>
			    </select>

					<input type="hidden" name="a_loc" id="a_loc" value="<?php echo $code_str;?>">
					<input type="hidden" name="aid" value="<?php echo $aid?>">
			</div>
		</div>

		<div class="form-group col-md-12 col-sm-12 col-xs-12 text-center">
				<input class="submit-button" type="submit" name="submit" value="<?php if($tar_count ==0){echo $this->get_label('add location');}else{echo $this->get_label('update');}?>" />
		</div>

	</div>
</div>

<?php $form->end(); ?>
<script type="text/javascript">
sortSelect(document.getElementById('loc'));
</script>
<?php $this->dispatch("layout/footer_iframe");?>
