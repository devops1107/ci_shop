<style>
.profile_box {
    background: rgb(241, 239, 239);
    border: 1px solid rgb(222, 222, 222);
    border-bottom: 0;
    padding: 15px 0px 0px 0px;
}
.profile_img {
    text-align: center;
}
.profile_box .profile_img img {
    width: 140px;
    border: 1px solid rgb(132, 132, 132);
    height: 140px;
    text-align: center;
    margin: 0 auto;
    border-radius: 50%;
}
.profile_img .profile_body {
    padding: 10px 0px;
}
.profile_img .profile_body p {
    margin-bottom: 1px;
}
.details_box {
    display: inline-block;
    width: 100%;
    background: rgb(230, 230, 230);
    padding: 15px 15px;
    border: 1px solid rgb(208, 208, 208);
}
.driver_details_unedit {
    padding-top: 10px;
}
.driver_details_box {
    display: inline-block;
    width: 100%;
    margin-bottom: 10px;
}
.driver_details_box label {
    width: 50%;
    float: left;
    margin: 3px 0px;
}
.driver_details_box span {
    display: inline-block;
    width: 50%;
    float: left;
}
.menu-ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: inline-block;
    width: 100%;
    background: rgb(234, 234, 234);
    margin: 15px 0px;
}
.menu-ul li {
    float: left;
}
.menu-ul li a.active {
    border-bottom: 2px solid rgb(0, 175, 251);
}
.menu-ul li a {
    display: inline-block;
    padding: 10px 15px;
    border-bottom: 2px solid rgba(0, 0, 0, 0);
    color: rgb(84, 84, 84);
}

</style>
<?php 
$uri1	=	$this->uri->segment(2);
$uri2	=	$this->uri->segment(3);
$uri3	=	$this->uri->segment(4);
//pr($uri1,1);
//pr($user_detail,1);

?>
<div id="category_response_main"></div>
<!--<div class="profile_box">
	<div class="col-md-6 margin_auto">
		<div class="profile_img">
			<!--<img class="" src="<?=($user_detail['profileimage']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/users/'.$user_detail['profileimage']))?UPLOAD_URL.'users/'.$user_detail['profileimage']:UPLOAD_URL.'users/no_image.png'?>" />-->
			<!--<div class="profile_body">
				<!--<p><strong><?= $user_detail['full_name']?></strong></p>-->
				<!--<p><?= $user_detail['emailid']?></p>
			</div>
		</div>
	</div>
</div>-->
<div class="details_box">	
	<div class="row driver_details_unedit">
		<div class="col-md-6 driver_details_box">
			<label>Email Id :</label>
			<span>
				<?= $user_detail['emailid']?>
			</span>
		</div>
		<!--<div class="col-md-6 driver_details_box">
			<label>Age :</label>
			<span>
				<?= $user_detail['age']?>
			</span>
		</div>
		<div class="col-md-6 driver_details_box">
			<label>User Name :</label>
			<span>
				<?= ($user_detail['username']!="")?$user_detail['username']:'' ?>
			</span>
		</div>
		<div class="col-md-6 driver_details_box">
			<label>Address :</label>
			<span>
				<?= ($user_detail['address']!="")?$user_detail['address']:$user_detail['street_address']." ".$user_detail['city']." ".$user_detail['state']." ".$user_detail['zip_code'] ?>
			</span>
		</div>
		<div class="col-md-6 driver_details_box">
			<label>Refferal Code :</label>
			<span>
				<?= ($user_detail['referral_code']!="")?$user_detail['referral_code']:"N/A"?>
			</span>
		</div>
		<?php if($user_detail['refferer_user_id']!=0 && $user_detail['refferer_code']!="") { ?>
			<div class="col-md-6 driver_details_box">
				<label>Reffered By :</label>
				<span>
					<?= ($user_detail['refferer_name']!="")?$user_detail['refferer_name']:"N/A"?>
					(<?= ($user_detail['refferer_code']!="")?$user_detail['refferer_code']:"N/A"?>)
				</span>
			</div>
		<?php } ?> -->
		<div class="col-md-6 driver_details_box">
			<label>Total Rooms Joined :</label>
			<span>
				<?= $user_detail['total_rooms_joined']?>
			</span>
		</div>
		<!-- <div class="col-md-6 driver_details_box">
			<label>Total User Tokens :</label>
			<span>
				<?= $user_detail['user_tokens']?>
			</span>
		</div>
		<div class="col-md-6 driver_details_box">
			<label>Total Number of Winning Lifetimes :</label>
			<span>
				<?= $user_detail['total_winning']?>
			</span>
		</div>
		<div class="col-md-6 driver_details_box">
			<label>Total wining of this year :</label>
			<span>
				<?= $user_detail['total_winning']?>
			</span>
		</div>
		<div class="col-md-6">
			<label><input type="checkbox" name="is_irs_confirm_status" value="1" id="is_irs_confirm" <?= ($user_detail['is_irs_confirm_status']!=0)?'checked':""?>> IRS Information Confirm Status</label>
		</div> -->
		
	</div>
</div>

<ul class="menu-ul">
	<li>
		<a href="<?= base_url('admin/view-user/').base64_encode($user_detail['id']) ?>" class="<?= ($uri1 == "view-user")?"active":"" ?>">Details</a> 
	</li>   
	<!-- <li>
		<a href="<?= base_url('admin/user-purchase-ticket/').base64_encode($user_detail['id'])."/TOKENS" ?>" class="<?= ($uri1 == "user-purchase-ticket")?"active":"" ?>">Purchased Tickets</a> 
	</li>   
	<li>
		<a href="<?= base_url('admin/user-direct-purchase/').base64_encode($user_detail['id'])."/DIRECT" ?>" class="<?= ($uri1 == "user-direct-purchase")?"active":"" ?>">Direct Purchased</a> 
	</li>    -->
	<li>
		<a href="<?= base_url('admin/user-joined-rooms/').base64_encode($user_detail['id']) ?>" class="<?= ($uri1 == "user-joined-rooms")?"active":"" ?>">Joined Rooms</a> 
	</li>   
	<!-- <li>
		<a href="<?= base_url('admin/user-token-history/').base64_encode($user_detail['id']) ?>" class="<?= ($uri1 == "user-token-history")?"active":"" ?>">Token History</a> 
	</li>   
	<li>
		<a href="<?= base_url('admin/user-irs-information/').base64_encode($user_detail['id']) ?>" class="<?= ($uri1 == "user-irs-information")?"active":"" ?>">IRS Information</a> 
	</li>       -->
	<li>
		<a href="<?= base_url('admin/user-winnings/').base64_encode($user_detail['id']) ?>" class="<?= ($uri1 == "user-winnings")?"active":"" ?>">Winnings</a> 
	</li>
	  
		
</ul>

<div class="content_body">
		
</div>

<script>
$('#is_irs_confirm').click(function(e) {
	if (confirm('Are you sure to change IRS information confirm status?')) {
		e.preventDefault();
		var is_confirm_status;
		if($(this).is(":checked")) {
			//alert("checked");
			is_confirm_status =1;
		} else {
			//alert("unchecked");
			is_confirm_status =0;
		}
		//alert(is_confirm_status);
		$.ajax( {
			"dataType": 'json',
			"url": '<?=base_url('admin/update-irs-status/'.$this->uri->segment(3))?>', 
			"type": "POST",
			"data": {'is_confirm_status':is_confirm_status},
			 
			"success": function(data){
				if(data.status=='success')
				{
					$('#category_response_main').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> '+data.message+'</div>');
					window.setTimeout(function(){location.reload()},2000)
				}else if(data.status=='error'){
					$('#add_category_response').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong> '+data.message+'</div>');
					window.setTimeout(function(){location.reload()},2000)
				}
			}
		});
	} else {
		return false;
	}
});
</script>