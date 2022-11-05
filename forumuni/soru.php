<?
	require_once("core.php");
	
	head();
	
	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id']; 
	
	$id = (int) $_GET['soruid'];

	if (empty($id)) {
		echo '<meta http-equiv="refresh" content="0; url=blog.php">';
		exit;
	}
	
	
	$soru = mysqli_query($connect,"select * from sorular where soru_id = '$id'");
	$veri = mysqli_fetch_assoc($soru);
	
	if(!$veri)
	{
		echo '<meta http-equiv="refresh" content="0; url=404.php">';
	}

	if(isset($_POST["cevapgonder"]))
	{
		$cevap = $_POST["cevap"];
		$zaman = time();
		$gonder = mysqli_query($connect,"insert into cevaplar(soru_id, id, cevap, cevap_tarih) values('$id','$user_id','$cevap','$zaman')");
		
		if($gonder)
		{			
			$rezuser = mysqli_query($connect,"select * from rezler where soru_id = '$id'");

			while($rez = $rezuser->fetch_array())
			{
				$user = $rez["user_id"];
				mysqli_query($connect,"insert into bildirimler(bildirim_katid, kime_user, user_id, soru_id, cevap, bildirim_tarih) values('2', '$user','$user_id','$id', '$cevap','$zaman')");
			}
			mysqli_query($connect,"insert into bildirimler(bildirim_katid, kime_user, user_id, soru_id, cevap, bildirim_tarih) values('2', '".$veri["id"]."','$user_id','$id', '$cevap','$zaman')");
			echo '<meta http-equiv="refresh" content="0;">';
		}
		else
		{
			echo "<div class='alert alert-danger'>Sanırım sunucumuzda bir sorun var. Sonra tekrar deneyin.</div>";
		}
	}
	
	$cevapid = mysqli_query($connect,"select * from cevaplar where soru_id = '$id' order by cevap_id desc");

	$cevapetkt = mysqli_query($connect,"select * from cevaplar where soru_id = '$id'");
	$cikti = mysqli_fetch_array($cevapetkt);

	if(isset($_POST["cevapgonderetkt"]))
	{
		$cevap = $_POST["cevap"];
		$cevapid = $_POST["cevapid"];
		$etket = $_POST["etiketlenen"];
		
		$zaman = time();

		$gonder = mysqli_query($connect,"insert into cevaplar(cevapla_id, id, soru_id, etiketlenen, cevap, cevap_tarih) values('$cevapid','$user_id', '$id', '$etket','$cevap','$zaman')");
		
		if($gonder)
		{

			mysqli_query($connect,"insert into bildirimler(bildirim_katid, kime_user, user_id, soru_id, cevap, bildirim_tarih) values('4', '$etket','$user_id','$id', '$cevap','$zaman')");
			echo '<meta http-equiv="refresh" content="0;">';
		}
		else
		{
			echo "<div class='alert alert-danger'>Sanırım sunucumuzda bir sorun var. Sonra tekrar deneyin.</div>";
		}
	}

	
	$usern = mysqli_query($connect, "select * from users where id = '".$veri["id"]."'");
	$usernam = mysqli_fetch_array($usern);
	
	$unigetir = mysqli_query($connect, "select * from universite where universite_id = '".$usernam["uni"]."'");
	$unigetir1 = mysqli_fetch_array($unigetir);
	
	$sikayet = mysqli_query($connect, "select * from sikayetler where sikayeteden = '$user_id' and soru_id = '$id'");
	$sikayet = mysqli_fetch_assoc($sikayet);

	$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$veri["soru_id"]."'");
	$rez = mysqli_fetch_array($rez);

	$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$veri["soru_id"]."' and user_id = '$user_id'");
	$rezs = mysqli_fetch_array($rezs);
	
	if($_POST["sikayet"])
	{
		if($sikayet["sikayeteden"] == $user_id && $sikayet["soru_id"] == $id)
		{
			echo "<div class='alert alert-danger'>Bu soruyu daha önce şikayet ettiniz!</div>";
		}
		else
		{
			$sikayet = mysqli_query($connect,"insert into sikayetler(soru_id, sikayeteden) values('".$id."','".$user_id."')");
			echo "<div class='alert alert-success'>Şikayetiniz iletildi.</div>";
		}
	}
	
	$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$veri["uni_id"]."'");
	$uniget = mysqli_fetch_array($uniget);
	
	$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$veri["fak_id"]."'");
	$fakget = mysqli_fetch_array($fakget);
	
	$bolget = mysqli_query($connect, "select * from bolumler where bolum_id = '".$veri["bol_id"]."'");
	$bolget = mysqli_fetch_array($bolget);
	
?>
<div class="container" style="margin-top:20px;">
<div class="row">
	<br/>
	<? sidebar(); ?>
	<div class="col-6 cardmob">
	<div class="card mb-3 gon">
		<div class="card-body">
			<div class="d-flex flex-row align-items-center" >
				<a class="card-subtitle mb-2 text-muted" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" >
					<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px; margin-top:12px;" width="30" height="30" />
				</a>
				<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" ><? echo $usernam["username"];?> </a> 
					<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($veri["soru_tarih"]); ?></small>
					<br/>
					<small style="font-size:12px;" class="text-muted">
					<? if($veri["kat_id"] == 2){ ?><span class="text-muted" href=""><a style="color:grey; font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 100%; text-overflow: ellipsis;" href="universitelerc.php?uniid=<? echo $veri["uni_id"]; ?>"><? echo $uniget["name"]; ?></a></span><? } ?>
					<? if($veri["kat_id"] == 3){ ?><span class="text-muted" href=""><a style="color:grey; font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;" href="universitelerc.php?uniid=<? echo $veri["uni_id"]; ?>"><? echo $fakget["name"]," / ",$uniget["name"]; ?></a></span><? } ?>
					<? if($veri["kat_id"] == 4){ ?><span class="text-muted" href=""><? echo $bolget["bolum_adi"]; ?></span><? } ?>
					</small>
				</p>
			</div>
			<div class="d-flex justify-content-between">
				<div style="padding:5px 37px;">
					<p class="soruinde"><? echo substr($veri["soru"],0,75); ?></p>
				</div>
				<? if($logged == 'Yes'){ ?>
				<div class="drpdown" style="top:10; right:5px; position:absolute;">
					<a href="#" class="nav-link link-dark" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu">
						<li><form action="" method="post"><input type="submit" class="dropdown-item" name="sikayet" value="Şikayet Et" target="_blank" /></form></li>
						<? if($user_id == $veri["id"]) { ?><li><a class="dropdown-item" href="soruduzenle.php?soruid=<? echo $veri["soru_id"]; ?>"> Düzenle</a></li><?}?>
					</ul>
				</div>
				<? } ?>
				<? if($logged == 'Yes' && $veri["id"] != $user_id){ ?>
				<div class="d-flex flex-row align-items-center" >
					<div id="rezbtn" style="position:absolute; bottom:5; right:5;">
						<button style="font-size:15px; float:left; <? if($veri["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $veri["soru_id"]; ?>">
						<span>#Rezle <? echo $rez["rez_id"]; ?></span>
						</button>
					</div>
				</div>
				<? } ?>
			</div>
		</div>
	</div>


	<? if($uname != "" ){ ?>

		

	<div class="cevapladiv">
		<form action="" method="POST">
			<div class="cevapla" style="z-index:1;"><br/>
				<textarea style="max-height:60px;" class="form-control" id="cevap" placeholder="Soruyu Cevapla" name="cevap" rows="3" required></textarea>
				<button type="submit" class="btn btn-primary px-2 ms-3" id="cevapbtn" name="cevapgonder">Cevapla</button>
			</div><br/><br/>
		</form>
	</div>



	<? } else { ?>
		<div class='alert alert-warning'style="margin-bottom:65px; margin-top:-10px;">Cevaplamak için hemen <a href="login.php">Üye Ol</a></div>
	<? } 
	
	$cevap = mysqli_query($connect,"select count(*) as cevap_id from cevaplar where soru_id = '$id'");
	$cevap = mysqli_fetch_array($cevap);

	?>
	
	<h5 style="margin-top:-50px; margin-left:17px; color:#2b3548; margin-bottom:-30px; font-size:18px;">Cevaplar (<? echo $cevap["cevap_id"]; ?>)</h5>
	<br/><br/>
	<? 	while ($cikti = $cevapid->fetch_array()) { 
			$userid = $cikti["id"];
			
			$querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$userid' LIMIT 1");
			if (mysqli_num_rows($querych) > 0) {
				$rowch = mysqli_fetch_assoc($querych);
				$aavatar = $rowch['avatar'];
				$aauthor = $rowch['username'];
				$uuni = $rowch['uni'];
				
				$useruni = mysqli_query($connect,"select * from universite where universite_id = '$uuni'");
				if (mysqli_num_rows($querych) > 0) {
					$owch = mysqli_fetch_assoc($useruni);
					$uniname = $owch['name'];
				}
			}
			
			$unigetir = mysqli_query($connect, "select * from universite where universite_id = '".$usernam["uni"]."'");
			$unigetir1 = mysqli_fetch_array($unigetir);

			$etktgetir = mysqli_query($connect, "select * from users where id = '".$cikti["etiketlenen"]."'");
			$etktgetir = mysqli_fetch_array($etktgetir);
	?>
		<div class="card mb-3 gon">
			<div class="card-body">
				<div class="d-flex flex-row align-items-center">
					<a class="card-subtitle mb-2 text-muted" href="<? if($aauthor == $uname){ echo "profilim"; } else { echo $aauthor; }?>" >
						<img src="<? echo $aavatar; ?>" alt="avatar" style="border-radius:100px; margin-top:12px;" width="30" height="30" />
					</a>
					<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($aauthor == $uname){ echo "profilim"; } else { echo $aauthor; }?>" ><? echo $aauthor;?> </a> 
						<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($cikti["cevap_tarih"]); ?></small>
						<br/>
						<small style="font-size:12px;" class="text-muted">
						<span class="text-muted" href=""><a style="color:grey; font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 100%; text-overflow: ellipsis;" href="universitelerc.php?uniid=<? echo $uuni; ?>"><? echo $uniname; ?></a></span>
						</small>
					</p>
				</div>
				<div class="d-flex justify-content-between">
					<div style="padding:5px 37px;">
						<p class="soruinde"><? if($cikti["etiketlenen"] != 0){ echo "<a href='",$etktgetir["username"],"'>@",$etktgetir["username"],"</a> ",$cikti["cevap"]; } else{ echo $cikti["cevap"]; } ?></p>
					</div>
				</div>
			</div>
			<? if($logged == 'Yes' && $aauthor != $uname){ ?>
				<br/>
			<button id="yanitla" class="px-1 yanitla" title="<? echo $cikti["cevap_id"]; ?>" style="right:15; bottom:15; position:absolute;">Yanıtla</button>
			<? } ?>
		</div>
	<? } ?>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	</div>
	<? sidebar(); ?>
	</div>
	</div>
</div>
</div>
<?
	footer();
?>