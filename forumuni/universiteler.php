<?php

	include "core.php";
	
	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id'];
	
	head();

	/*if ($logged == 'No') {
		echo '<meta http-equiv="refresh" content="0;url=login.php">';
		exit;
	}*/

	$sorgu = $connect->query("SELECT * FROM universite");

	if ($connect->errno > 0) {
		die("<b>Sorgu Hatası:</b> " . $connect->error);
	}
?>

	<div class="form-group" style="margin-top:15px;">
		<input type="text" class="form-control input" placeholder="Üniversite veya Kullanıcı Ara" name="eftal"/><br/>
		<div id="sonuclar"></div>
	</div>
	<div class="container">
		<div class="row">
		<? sidebar();?>
			<div id="kesfetanilar" class="col-6 cardmob">
					<?php
						$ani = mysqli_query($connect,"select * from anilar order by ani_id desc");
						while ($oku = mysqli_fetch_assoc($ani)) 
						{
							$sonuc2 = mysqli_query($connect,"select * from users where id = '$oku[id]'");
							
							if(mysqli_num_rows($sonuc2)!=0)
							{
								while($oku1 = mysqli_fetch_assoc($sonuc2))
								{
									$metin1="$oku1[id]";
									$metin2="$oku1[username]";
									$metin3="$oku1[avatar]";
									
									$unigetir = mysqli_query($connect, "select * from universite where universite_id in(select uni from users where id = '$metin1')");
									$unigetir1 = mysqli_fetch_array($unigetir);
								}
							}
							
							$ani1 = "$oku[ani]";
					?>
						<div class="card mb-3 gon" >
							<div class="card-body">
								<p><? echo $ani1; ?><br/>
									<small class="text-muted"><? echo $unigetir1["name"];?></small>
								</p>
								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="<? if($metin2 == $uname){ echo "profilim"; } else { echo $metin2; }?>" >
											<img src="<? echo $metin3; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($metin2 == $uname){ echo "profilim"; } else { echo $metin2; }?>" ><? echo $metin2;?> </a> 
											<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($oku["ani_tarih"]); ?></small>
										</p>
									</div>
								</div>
							</div>
						</div>
					<? } ?>
			</div>
			
			<? sidebar(); ?>
		</div>
		
	</div>
	<button class="anibtnkesfet" id="anibtn" onclick="aniac()">
		<span class="fas fa-plus"></span>
	</button>
	<div class="animenu container" id="aniform" style="display:none;">
		<?
		
			if(isset($_POST["anigonder"]))
			{
				$zaman = time();
				$sql="insert into anilar(ani,id,ani_tarih) values('".$_POST["ani"]."','$user_id','$zaman')";
				$sonuc=mysqli_query($connect,$sql);
				echo '<meta http-equiv="refresh" content="0;url=kesfet">';
			}

		?>
		<center>
		<form action="" method="POST" style="margin-top:30px; margin-left:-15px;">
			<p id="anikapa" onclick="anikapa()">İptal Et</p>
			<button class="btn btn-primary px-4 ms-3" style="float:right;  margin-top:-10px;" name="anigonder">Anıla</button><br/><br/>
			<div class="form-group">
				<textarea class="form-control" name="ani" id="ani" placeholder="Anı Gir" rows="3" required></textarea><br/><br/>
			</div>
			<div class="alert alert-info" role="alert">Kurallara uymayı unutma.</div>
		</form>
		</center>
	</div>
</div>
<?php
footer();
?>