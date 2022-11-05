<?php
include "core.php";
head();

	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id']; 
	
	$user = $_GET['username'];

	if (empty($user)) {
		echo '<meta http-equiv="refresh" content="0; url=404.php">';
		exit;
	}

	$runq = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$user'");

	$row = mysqli_fetch_assoc($runq);

	if(!$row)
	{
		echo '<meta http-equiv="refresh" content="0; url=404.php">';
	}

?>
		<?php
		
			$sonuc1 = mysqli_query($connect,"select * from users where username = '$user'");
				
			if(mysqli_num_rows($sonuc1)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc1))
				{
					$metin="$oku[username]";
					$metin7="$oku[adsoyad]";
					$metin1="$oku[avatar]";
					$metin2="$oku[role]";
					$metin8="$oku[uni]";
				}
			}
			
			$sonuc2 = mysqli_query($connect,"select name from universite where universite_id = '$metin8'");
				
			if(mysqli_num_rows($sonuc2)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc2))
				{
					$metin3="$oku[name]";
				}
			}

			$sonuc2 = mysqli_query($connect,"select name from universite_fakulte where fakulte_id in(select uni_fakulte from users where username = '$user')");
						
			if(mysqli_num_rows($sonuc2)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc2))
				{
					$fak="$oku[name]";
				}
			}

			$sonuc2 = mysqli_query($connect,"select bolum_adi from bolumler where bolum_id in(select uni_bolum from users where username = '$user')");
				
			if(mysqli_num_rows($sonuc2)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc2))
				{
					$bolum="$oku[bolum_adi]";
				}
			}
		?>

		<div class="container mt-5">
			<center><div class="rounded-circle" style="width:150px;height:150px;overflow:hidden;margin:0;">
				<img src="<? echo $metin1; ?>" style="display:block;width:150px;margin:-5px 0;" />
			</div></center>
			<div class="text-center mt-3">
				<span class="bg-secondary p-1 px-4 rounded text-white"><? echo $metin2; ?></span>
				<h5 class="mt-2 mb-0"><? echo $metin7; ?></h5>
				<p class="mt-2 text-muted" style="margin-bottom:-10px; font-size:14px;">
							<? echo $metin3; ?><br/>
							<? echo $fak; ?><br/>
							<? echo $bolum; ?><br/>
						</p>
				<br/>
				<? 
					$sonuc6 = mysqli_query($connect,"select yorum from uni_comment where id in(select id from users where username = '$user')");

					if(mysqli_num_rows($sonuc6)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc6))
						{
							$degerlendirme="$oku[yorum]";
						}
					}	
				?>
					<div id="degerof">
						<div id="degerlen"><br/><br/>
							<center>
								<h5><? echo $metin3; ?></h5>
								<b>Değerlendirmesi</b>
							</center><br/>
							<? echo $degerlendirme; ?>
						</div>
						<center>
							<p class="tikladeg">
								Değerlendirme Gör
							</p>
						</center>
					</div>
				<ul class="nav nav-pills nav-fill">
					<li role="button" class="nav-item" id="sorulara" onclick="sorularac()">
						<a class="nav-link" id="sorulnk">Sorular</a>
					</li>
					<li role="button" class="nav-item" id="anilara" onclick="anilarac()">
						<a class="nav-link" id="anilnk" >Anılar</a>
					</li>
				</ul><br/>
			</div> 
		</div>
		
		<?
			$runq = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$user'");

			$row = mysqli_fetch_assoc($runq);
		
			$sonuc1 = mysqli_query($connect,"select * from users where username = '$user'");
				
			if(mysqli_num_rows($sonuc1)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc1))
				{
					$metin="$oku[username]";
					$metin1="$oku[avatar]";
					$metin2="$oku[role]";
				}
			}
			
			$sonuc2 = mysqli_query($connect,"select name from universite where universite_id in(select uni from users where username = '$user')");
				
			if(mysqli_num_rows($sonuc2)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc2))
				{
					$metin3="$oku[name]";
					$metin4="$oku[universite_id]";
				}
			}
			
			$ani = mysqli_query($connect,"select * from anilar where id in(select id from users where username = '$user') order by ani_id desc");
			$soru = mysqli_query($connect,"select * from sorular where id in(select id from users where username = '$user') order by soru_id desc");

		?>
		<div id="sorulardiv">
			<? while ($oku = mysqli_fetch_assoc($soru)) { $soru1 = "$oku[soru]"; 

				$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$oku["uni_id"]."'");
				$uniget = mysqli_fetch_array($uniget);

				$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$oku["fak_id"]."'");
				$fakget = mysqli_fetch_array($fakget);

				$bolget = mysqli_query($connect, "select * from bolumler where bolum_id = '".$oku["bol_id"]."'");
				$bolget = mysqli_fetch_array($bolget);

				$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$oku["soru_id"]."'");
				$rez = mysqli_fetch_array($rez);

				$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$oku["soru_id"]."' and user_id = '$user_id'");
				$rezs = mysqli_fetch_array($rezs);

				$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$oku["soru_id"]."'");
				$cevap = mysqli_fetch_array($cevap);
			
			?>
			<div class="card mb-4" style="position:relative; margin:-10px 10px; width:95%; border-radius:15px; box-shadow: 3px 3px 3px #d3d3d3;">
          		<div class="card-body">
				  	<div onclick="location.href='soru.php?soruid=<? echo $oku['soru_id']; ?>'">
						<p class="soruindex"><? echo substr($oku["soru"],0,75); ?><small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($oku["soru_tarih"]); ?></small>
						<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;"> 
						<?	
							if($oku["kat_id"] == 1){ echo " Genel Sorular"; } 
							if($oku["kat_id"] == 2){ echo " ",$uniget["name"]; } 
							if($oku["kat_id"] == 3){ echo " ",$fakget["name"]," | ",substr($uniget["name"],0,15),"..."; } 
							if($oku["kat_id"] == 4){ echo " ",$bolget["bolum_adi"]; } 
							if($oku["kat_id"] == 5){ echo " Yurtlar Hakkında"; } 
						?>
						</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
					</p>
					</div>
					<div class="d-flex justify-content-between">
						<div class="d-flex flex-row align-items-center">
							<a class="card-subtitle mb-2 text-muted" href="<? echo $user; ?>" >
								<img src="<? echo $metin1; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
							</a>
							<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? echo $user; ?>" ><? echo $user;?> </a> </p>
						</div>
						<? if($logged == 'Yes'){ ?>
						<div class="d-flex flex-row align-items-center">
							<div id="rezbtn">
								<button style="font-size:15px; float:left; <? if($oku["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $oku["soru_id"]; ?>">
								<span>#Rezle <? echo $rez["rez_id"]; ?></span>
								</button>
							</div>
						</div>
						<? } ?>	
					</div>
				</div>
				<div class="drpdown" style="top:10; right:5px; position:absolute; float:right;">
					<a href="#" class="nav-link link-dark" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="soru.php?soruid=<? echo $sorus["soru_id"]; ?>"> Soruyu Gör</a></li>
						<li role="separator" class="divider"></li>
					</ul>
				</div>
			</div>
			<? } ?>
		</div>
		<div id="anilardiv">
			<? while ($oku = mysqli_fetch_assoc($ani)) { $ani1 = "$oku[ani]"; ?>
				<div class="card mb-4" style="position:relative; margin:-10px 10px; width:95%; border-radius:15px; box-shadow: 3px 3px 3px #d3d3d3;">
					<div class="card-body">
						<p class="soruindexa"><? echo $ani1; ?></p>

						<div class="d-flex justify-content-between">
							<div class="d-flex flex-row align-items-center">
								<a class="card-subtitle mb-2 text-muted" href="<? echo $user; ?>" >
									<img src="<? echo $metin1; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
								</a>
								<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? echo $user; ?>" ><? echo $user;?> </a> 
									<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($oku["ani_tarih"]); ?></small>
								</p>	
							</div>
						</div>
					</div>
					<div class="drpdown" style="top:10; right:5px; position:absolute; float:right;">
						<a href="#" class="nav-link link-dark" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href=""> Düzenle</a></li>
							<li><a class="dropdown-item" href="?delete-idani=<?echo $oku['ani_id'];?>"> Sil</a></li>
						</ul>
					</div>
				</div>
			<? } ?>
		</div>
	</div>
</div>
<?php

footer();
?>