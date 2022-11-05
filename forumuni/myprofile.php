<?php
include "core.php";
head();
if ($logged == 'No') {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit;
}

?>
	<div id="profil">
				<?php 
					$uname = $_SESSION['sec-username']; 
					$user_id = $rowu['id']; 
				
					$sonuc1 = mysqli_query($connect,"select * from users where username = '$uname'");
						
					if(mysqli_num_rows($sonuc1)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc1))
						{
							$metin="$oku[username]";
							$metin7="$oku[adsoyad]";
							$metin1="$oku[avatar]";
							$metin2="$oku[role]";
						}
					}
					
					$sonuc2 = mysqli_query($connect,"select name from universite where universite_id in(select uni from users where username = '$uname')");
						
					if(mysqli_num_rows($sonuc2)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc2))
						{
							$metin3="$oku[name]";
						}
					}

					$sonuc2 = mysqli_query($connect,"select name from universite_fakulte where fakulte_id in(select uni_fakulte from users where username = '$uname')");
						
					if(mysqli_num_rows($sonuc2)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc2))
						{
							$fak="$oku[name]";
						}
					}

					$sonuc2 = mysqli_query($connect,"select bolum_adi from bolumler where bolum_id in(select uni_bolum from users where username = '$uname')");
						
					if(mysqli_num_rows($sonuc2)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc2))
						{
							$bolum="$oku[bolum_adi]";
						}
					}
					
					$sonuc2 = mysqli_query($connect,"select id,uni from users where username = '$uname'");
						
					if(mysqli_num_rows($sonuc2)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc2))
						{
							$metin4="$oku[id]";
							$metin5="$oku[uni]";
						}
					}
					
					if(isset($_POST["gonder"]))
					{
						$zaman = time();
						$sql="insert into uni_comment(yorum,memnuniyet,id,universite_id,yorum_tarih) values('".$_POST["yorum"]."','".$_POST["mem"]."','$metin4','$metin5','$zaman')";
						$sonuc=mysqli_query($connect,$sql);
						if($sonuc)
						{

							$uniuser = mysqli_query($connect,"select * from users where uni = '$metin5'");

							while($unius = $uniuser->fetch_array())
							{
								$user = $unius["id"];
								mysqli_query($connect,"insert into bildirimler(bildirim_katid, kime_user, user_id, yorum, uni_id, bildirim_tarih) values('3', '$user','$user_id','".$_POST["yorum"]."', '$metin5','$zaman')");
								echo '<meta http-equiv="refresh" content="0.5;">';
							}
						}
					}
					
					$sonuc6 = mysqli_query($connect,"select count(id) from uni_comment where id in(select id from users where username = '$uname')");
					
					$tek = mysqli_fetch_array($sonuc6);
					
					$sonuc7 = mysqli_query($connect,"select uni from users where username = '$uname'");
						
					if(mysqli_num_rows($sonuc7)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc7))
						{
							$metin6="$oku[uni]";
						}
					}
					
					
					$soru = mysqli_query($connect, "select * from sorular where id in(select id from users where username = '$uname') order by soru_id desc");
					
					if (isset($_GET['delete-id'])) {
						$soruid    = (int) $_GET["delete-id"];
						$sil = mysqli_query($connect, "DELETE FROM sorular WHERE soru_id = '$soruid'");
						echo "<div class='alert alert-success'>Soru başarıyla silindi</div>";
						echo '<meta http-equiv="refresh" content="1;url=profilim">';
					}

					if (isset($_GET['delete-idani'])) {
						$aniid    = (int) $_GET["delete-idani"];
						$sil = mysqli_query($connect, "DELETE FROM anilar WHERE ani_id = '$aniid'");
						echo "<div class='alert alert-success'>Soru başarıyla silindi</div>";
						echo '<meta http-equiv="refresh" content="1;url=profilim">';
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
						<div class="buttons">
							<? if($tek[0] != 0){ 
								$sonuc6 = mysqli_query($connect,"select yorum from uni_comment where id in(select id from users where username = '$uname')");
					
								if(mysqli_num_rows($sonuc6)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc6))
									{
										$degerlendirme="$oku[yorum]";
									}
								}	
							?>
								<div id="degerof">
									<div id="degerlen">
										<br/>
										<br/>
										<center>
											<h5><? echo $metin3; ?></h5>
											<b>Değerlendirmeniz</b>
										</center>
										<br/>
										<? echo substr($degerlendirme,0,300); ?>
									</div>
									<center>
										<p class="tikladeg">
											Değerlendirme Gör
										</p>
									</center>
								</div>
							<? } ?>
							<button class="btn btn-outline-primary px-4" onclick="duzenle()">Profili Düzenle</button>
							<? 
							if($metin6 != "")
							{
								if($tek[0] == 0){ 
								?>
								<button class="btn btn-outline-primary px-4" onclick="degerlendir()">Değerlendirme Gir</button>
								<?
								}
							}
							?>
						</div><br/>
						<ul class="nav nav-pills nav-fill">
							<li role="button" class="nav-item" id="sorulara" onclick="sorularac()">
								<a class="nav-link" id="sorulnk">Sorularım</a>
							</li>
							<li role="button" class="nav-item" id="anilara" onclick="anilarac()">
								<a class="nav-link" href="#anilarim" id="anilnk" >Anılarım</a>
							</li>
						</ul>
						<br/>
						<? 
						if($metin6 != "")
						{
							if($tek[0] == 0){ 
							
						?>
						<div class="alert alert-info" id="deger">
							<i style="float:right;" class="fas fa-close closedege" role="button" onclick="closedege()"></i><br/><br/>
							<h5><? echo $metin3; ?></h5><br/>
							<form action="" method="POST">
								<div class="ratingp">
									<input type="radio" name="mem" value="5" id="5" required><label for="5">☆</label> 
									<input type="radio" name="mem" value="4" id="4"><label for="4">☆</label> 
									<input type="radio" name="mem" value="3" id="3"><label for="3">☆</label>
									<input type="radio" name="mem" value="2" id="2"><label for="2">☆</label> 
									<input type="radio" name="mem" value="1" id="1"><label for="1">☆</label>
									<p style="float:left;margin-top:10px;">Memnuniyet:</p>
								</div><br/>
								<textarea class="form-control" rows="4" placeholder="Üniversiteni Yorumla" name="yorum" required></textarea> <br/>
								<div class="alert alert-info" role="alert">Unutma, üniversitene yalnızca bir tane yorun yapabilir ve değiştiremezsin.</div>
								<button class="btn btn-primary px-4 ms-3" name="gonder">Yorumla</button>
							</form>
						</div>
						 
				<?php } 
					else{
						
						$sonuc6 = mysqli_query($connect,"select yorum from uni_comment where id in(select id from users where username = '$uname')");
					
						if(mysqli_num_rows($sonuc6)!=0)
						{
							while($oku = mysqli_fetch_assoc($sonuc6))
							{
								$degerlendirme="$oku[yorum]";
							}
						}
				   }}?>
			</div> 
				</div>
	<div class="container">
			<div class="row profildzn">
				<? sidebar(); ?>
				<div id="sorulardiv" class="col-9 cardmob">
					<? 
					
					$soru = mysqli_query($connect, "select * from sorular where id in(select id from users where username = '$uname') order by soru_id desc");

					while ($oku = mysqli_fetch_assoc($soru)) 
					{
							$soru1 = "$oku[soru]";
							
							$usern = mysqli_query($connect, "select * from users where id = '".$oku["id"]."'");
							$usernam = mysqli_fetch_array($usern);

							$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$oku["uni_id"]."'");
							$uniget = mysqli_fetch_array($uniget);
							
							$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$oku["fak_id"]."'");
							$fakget = mysqli_fetch_array($fakget);
							
							$bolget = mysqli_query($connect, "select * from bolumler where bolum_id = '".$oku["bol_id"]."'");
							$bolget = mysqli_fetch_array($bolget);
							
							$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$oku["soru_id"]."'");
							$cevap = mysqli_fetch_array($cevap);
					?>
					<div class="card mb-3 gon">
						<div class="card-body" onclick="location.href='soru.php?soruid=<? echo $oku['soru_id']; ?>'">
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

							<div class="d-flex justify-content-between">
								<div class="d-flex flex-row align-items-center">
									<a class="card-subtitle mb-2 text-muted" href="<? echo "profilim";?>" >
										<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
									</a>
									<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? echo "profilim"; ?>" ><? echo $usernam["username"];?> </a> </p>
								</div>
							</div>
						</div>
						<div class="drpdown" style="top:10; right:5px; position:absolute; float:right;">
							<a href="#" class="nav-link link-dark" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="soru.php?soruid=<? echo $oku["soru_id"]; ?>"> Soruyu Gör</a></li>
								<li><a class="dropdown-item" href="soruduzenle.php?soruid=<? echo $oku["soru_id"]; ?>"> Düzenle</a></li>
								<a class="dropdown-item" href="?delete-id=<?echo $oku['soru_id'];?>"> Sil</a>
							</ul>
						</div>
					</div>
					<? } ?><br/>
				</div>

				<button class="anibtn" id="sorubtn" onclick="soruac()">
					<span class="fas fa-plus"></span>
				</button>
				<div class="animenu container" id="soruform">
					<center>
					<?
					if (isset($_POST['sorugonder'])) {
								$soru 	= $_POST["soru"];
								$uni    = $_POST["MemberUni"];
								$katid    = $_POST["katid"];
								$fak    = $_POST["MemberFak"];
								$bolum  = $_POST["bolumler"];
								$zaman = time();

								$sql = "INSERT INTO `sorular` (kat_id, id, uni_id, fak_id, soru, bol_id, soru_tarih) VALUES ('$katid', '".$user_id."','$uni', '$fak', '$soru', '$bolum', '$zaman')";
								$sonuc = mysqli_query($connect,$sql);
								
								echo '<meta http-equiv="refresh" content="0;url=profilim">';

							}
							
							?>
					<form action="" method="POST" style="margin-top:30px; margin-left:-15px;">
					<p id="sorukapa" onclick="sorukapa()">İptal Et</p>
						<button type="submit" class="btn btn-primary px-4 ms-3" style="float:right; margin-top:-10px;" name="sorugonder">Soru Ekle</button>
						<div>
							<select id="kat" class="form-select" name="katid" id="katid" required>
								<option value="">Kategori Seç</option>
								<?php 
								$sonuc = mysqli_query($connect,"select * from sorucevapkat");
				
								if(mysqli_num_rows($sonuc)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc))
									{
									$metin1="$oku[kat_adi]";
									$metin2="$oku[kat_id]"; ?>
								<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?> <?php }} ?></option>
							</select>
						</div>
						<br/>
						<div class="uni" style="display:none;">
							<div class="input-group mb-3 needs-validation">
								<span class="input-group-text"><i class="fas fa-univerty"></i></span>
								<select  class="form-select" name="MemberUni" id="MemberUni">
									<option value="0">Üniversite Seç</option>
									<?php 
									$sonuc = mysqli_query($connect,"select * from universite order by name");
					
									if(mysqli_num_rows($sonuc)!=0)
									{
										while($oku = mysqli_fetch_assoc($sonuc))
										{
										$metin1="$oku[name]";
										$metin2="$oku[universite_id]"; ?>
									<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?> <?php }} ?></option>
								</select>
							</div>
						</div>
						<div class="fak" style="display:none;">
							<div class="input-group mb-3 needs-validation">
								<span class="input-group-text"><i class="fas fa-university"></i></span>
								<select  class="form-select" name="MemberFak" id="MemberFak">
									<option value="0">Fakülte Seç</option>
								</select>
							</div>
						</div>
						<div class="bol" style="display:none;">
							<div class="input-group mb-3 needs-validation">
								<span class="input-group-text"><i class="fas fa-university"></i></span>
								<select  class="form-select" name="bolumler" id="bolumler">
									<option value="0">Bölüm Seç</option>
									<?php 
									$sonuc = mysqli_query($connect,"select * from bolumler order by bolum_adi");
					
									if(mysqli_num_rows($sonuc)!=0)
									{
										while($oku = mysqli_fetch_assoc($sonuc))
										{
										$metin1="$oku[bolum_adi]";
										$metin2="$oku[bolum_id]"; ?>
									<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?> <?php }} ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<textarea class="form-control" id="soru" placeholder="Soru" name="soru" rows="3" required></textarea>
						</div><br/>
					</form>
					</center>
				</div>
			
			<!------ Anılarım ------>
			<div id="anilardiv" class="col-9 cardmob">
			<?php 
					
					$sonuc2 = mysqli_query($connect,"select * from users where username = '$uname'");
						
					if(mysqli_num_rows($sonuc2)!=0)
					{
						while($oku = mysqli_fetch_assoc($sonuc2))
						{
							$metin4="$oku[id]";
							$metin1="$oku[avatar]";
						}
					}
					$ani = mysqli_query($connect,"select * from anilar where id in(select id from users where username = '$uname') order by ani_id desc");
				?>	
					<? while ($oku = mysqli_fetch_assoc($ani)) 
					{
							$ani1 = "$oku[ani]";

							$unigetir = mysqli_query($connect, "select * from universite where universite_id in(select uni from users where username = '$uname')");
							$uniget = mysqli_fetch_array($unigetir);


					?>
					<div class="card mb-3 gon">
						<div class="card-body">
							<p class="soruindexa"><? echo $ani1; ?></p>

							<div class="d-flex justify-content-between">
								<div class="d-flex flex-row align-items-center">
									<a class="card-subtitle mb-2 text-muted" href="<? echo "profilim"; ?>" >
										<img src="<? echo $metin1; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
									</a>
									<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? echo "profilim"; ?>" ><? echo $usernam["username"];?> </a> 
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

			<button class="anibtn" id="anibtn" onclick="aniac()">
				<span class="fas fa-plus"></span>
			</button>
			<div class="animenu container" id="aniform" style="display:none;">
				<?
				
					if(isset($_POST["anigonder"]))
					{
						$zaman = time();
						$sql="insert into anilar(ani,id,ani_tarih) values('".$_POST["ani"]."','$metin4','$zaman')";
						$sonuc=mysqli_query($connect,$sql);
						echo '<meta http-equiv="refresh" content="0;url=profilim">';
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
		</div>
		</div>
	<!----- Profili Düzenle ---->
	
	<?
	if (isset($_POST['save'])) {
		$email    = $_POST['email'];
		$username = $_POST['username'];
		$avatar   = $rowu['avatar'];
		$password = $_POST['password'];
		$uni = $_POST['MemberUnia'];
		$fak = $_POST['MemberFaka'];
		$bolum = $_POST['MemberBolum'];

		$emused = 'No';
		
		$susere  = mysqli_query($connect, "SELECT * FROM `users` WHERE email='$email' && id != $user_id LIMIT 1");
		$countue = mysqli_num_rows($susere);
		if ($countue > 0) {
			$emused = 'Yes';
		}
		
		if (@$_FILES['avafile']['name'] != '') {
			$target_dir    = "uploads/avatars/";
			$target_file   = $target_dir . basename($_FILES["avafile"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$filename      = $uname . '.' . $imageFileType;
			
			$uploadOk = 1;
			
			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES["avafile"]["tmp_name"]);
			if ($check !== false) {
				$uploadOk = 1;
			} else {
				echo '<div class="alert alert-warning">Lütfen bir resim seçin.';
				$uploadOk = 0;
			}
			
			// Check file size
			if ($_FILES["avafile"]["size"] > 10000000) {
				echo '<div class="alert alert-warning">Üzgünüz, dosyanız çok büyük.</div>';
				$uploadOk = 0;
			}
			
			if ($uploadOk == 1) {
				move_uploaded_file($_FILES["avafile"]["tmp_name"], "uploads/avatars/" . $filename);
				$avatar = "uploads/avatars/" . $filename;
			}
		}
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL) && $emused == 'No') {
			
			if ($password != null) {
				$password = hash('sha256', $_POST['password']);
				$querysd  = mysqli_query($connect, "UPDATE `users` SET email='$email', username='$username', avatar='$avatar', password='$password', uni='$uni', uni_fakulte='$fak', uni_bolum='$bolum' WHERE id='$user_id'");
			} else {
				$querysd = mysqli_query($connect, "UPDATE `users` SET email='$email', username='$username', avatar='$avatar', uni='$uni', uni_fakulte='$fak', uni_bolum='$bolum' WHERE id='$user_id'");
			}
			
		}
		
		echo '<meta http-equiv="refresh" content="0;url=profilim">';
	}
	?>
	<br/>
	<div style="padding:15px;" id="duzenle">
		<form method="post" action="" enctype="multipart/form-data">
			<label for="username"><i class="fa fa-user"></i> Kullanıcı Adı:</label>
			<input type="text" name="username" id="username" value="<?php echo $rowu['username']; ?>" class="form-control" required />
			<br />
										
			<label for="email"><i class="fa fa-envelope"></i> E-Posta Adresi:</label>
			<input type="email" name="email" id="email" value="<?php echo $rowu['email']; ?>" class="form-control" required />
			<br />
			
			<label for="avatar"><i class="fa fa-image"></i> Profil Resmi:</label>
			<center><img src="<?php echo $rowu['avatar']; ?>" width="12%"></center>
			<div class="custom-file">
				<input type="file" class="form-control" name="avafile" accept="image/*" id="avatarfile">
			</div><br />
			
			<?php
			
			$sonuc = mysqli_query($connect,"select * from users where username = '$uname'");
			
			if(mysqli_num_rows($sonuc)!=0)
			{
				while($oku = mysqli_fetch_assoc($sonuc))
				{
					$metin1="$oku[uni]";
					$metin2="$oku[uni_fakulte]";
					$metin3="$oku[uni_bolum]";
				}
			}
			
			if($metin1 == "" && $metin2 == "" && $metin3 == "")
			{
				?>
					 <div id="unidegil">
						<div class="alert alert-info" role="alert"><center>
							Eğer üniversite kazandıysan tebrik ederiz. Üniversiteni ekle ve hemen değerlendirmeye başla. Ama unutma üniversite seçimi değiştirilemez.
						</center></div>
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberUnia" id="MemberUnia">
								<option value="0">Üniversite Seç</option>
								<?php 
								$sonuc = mysqli_query($connect,"select * from universite order by name");
				 
								if(mysqli_num_rows($sonuc)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc))
									{
									$metin1="$oku[name]";
									$metin2="$oku[universite_id]"; ?>
								<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?> <?php }} ?></option>
							</select>
						</div>
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberFaka" id="MemberFaka">
								<option value="0">Fakülte Seç</option>
							</select>
						</div>
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberBolum" id="MemberBolum">
								<option value="0">Bölüm Seç</option>
								<?php 
								$sonuc = mysqli_query($connect,"select * from bolumler order by bolum_adi");
				 
								if(mysqli_num_rows($sonuc)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc))
									{
									$metin1="$oku[bolum_adi]";
									$metin2="$oku[bolum_id]"; ?>
								<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?> <?php }} ?></option>
							</select>
						</div>
					</div>
				<?
			}
			
			else{
			?>
			<div id="unidegil">
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberUnia" id="MemberUnia">
								<?php
								$sonuc = mysqli_query($connect,"select * from universite where universite_id in(select uni from users where username = '$uname')");
			
								if(mysqli_num_rows($sonuc)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc))
									{
										$metin1="$oku[name]";
										$metin2="$oku[universite_id]";
									}
								}
								?>
								<option value="<?php echo $metin2; ?>"><?php echo "$metin1"; ?></option>
							</select>
						</div>
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberFaka" id="MemberFaka">
							<?
								$sonuc2 = mysqli_query($connect,"select * from universite_fakulte where fakulte_id in(select uni_fakulte from users where username = '$uname')");
			
								if(mysqli_num_rows($sonuc2)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc2))
									{
										$metin1="$oku[name]";
										$metin2="$oku[fakulte_id]";
									}
								}
							?>
								<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?></option>
							</select>
						</div>
						<div class="input-group mb-3 needs-validation">
							<span class="input-group-text"><i class="fas fa-university"></i></span>
							<select  class="form-select" name="MemberBolum" id="MemberBolum">
							<?
								$sonuc3 = mysqli_query($connect,"select * from bolumler where bolum_id in(select uni_bolum from users where username = '$uname')");
			
								if(mysqli_num_rows($sonuc3)!=0)
								{
									while($oku = mysqli_fetch_assoc($sonuc3))
									{
										$metin1="$oku[bolum_adi]";
										$metin2="$oku[bolum_id]";
									}
								}
							?>
								<option value="<?php echo "$metin2"; ?>"><?php echo "$metin1"; ?></option>
							</select>
						</div>
					</div>
			<?}?>
			<label for="name"><i class="fa fa-key"></i> Şifre:</label>
			<input type="password" name="password" id="name" value="" class="form-control" />
			<i>Bu alanı yalnızca şifrenizi değiştirmek istiyorsanız doldurun.</i>
			<br /><br />

			<input type="submit" name="save" class="btn btn-primary col-12" value="Kaydet" />
			<span class="btn col-12" onclick="iptalp()">İptal Et</span>
		</form>
	</div>
	
</div>
</div>
</div>
<?php

footer();
?>