<?
	include "core.php";
	
	head();
	
	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id'];
	
	$usera = mysqli_query($connect, "select * from users where username = '$uname'");
	$usera = mysqli_fetch_array($usera);
	
	$soru = mysqli_query($connect,"select * from sorucevapkat");
	
	
	$genels = mysqli_query($connect,"select * from sorular where kat_id = '1' order by soru_id desc");
?>
	<select id="sorusel"  class="form-select" style="width:50%; margin-top:20px; margin-bottom:20px; float:left;">
		<? while ($cikti = $soru->fetch_array()) { 
			
			$sorukat = mysqli_query($connect,"select count(*) as kat_id from sorular where kat_id = ".$cikti["kat_id"]."");
			$sorukat = mysqli_fetch_array($sorukat);
		
		?>
		<option value="<? echo $cikti["kat_id"]; ?>"> <? echo $cikti["kat_adi"]; ?> </option><span class="sorusayi"><? echo $sorukat["kat_id"]; ?></span>
		<? } ?>
	</select>
	
	<!--- Genel Sorular --->
<div class="container">
	<div class="row">
	<? sidebar(); ?>
		<div class="sorucevfilt">
			<div id="genels" class="row col-6 cardmob">
				<?while ($cikti = $genels->fetch_array()){
					
					$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
					$cikar = mysqli_fetch_array($cikar);

					$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
					$rez = mysqli_fetch_array($rez);

					$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
					$rezs = mysqli_fetch_array($rezs);
					
					$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
					$cevap = mysqli_fetch_array($cevap);
					
					?>
					<div class="card mb-3 gon">
						<div class="card-body">
							<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
								<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
								<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;"> 
									Genel Sorular
									</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
								</p>
							</div>
						
							<div class="d-flex justify-content-between">
								<div class="d-flex flex-row align-items-center">
									<a class="card-subtitle mb-2 text-muted" href="<? if($cikar["username"] == $uname){ echo "profilim"; } else { echo $cikar["username"]; }?>" >
										<img src="<? echo $cikar["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
									</a>
									<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($cikar["username"] == $uname){ echo "profilim"; } else { echo $cikar["username"]; }?>" ><? echo $cikar["username"];?> </a> 
										<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($cikti["soru_tarih"]); ?></small>
									</p>
								</div>
								<? if($logged == 'Yes' && $cikti["id"] != $user_id){ ?>
								<div class="d-flex flex-row align-items-center">
									<div id="rezbtn">
										<button style="font-size:15px; float:left; <? if($cikti["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $cikti["soru_id"]; ?>">
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
		</div>
	<? sidebar(); ?>
	</div>
	<button class="anibtn" id="sorubtn" onclick="soruac()">
		<span class="fas fa-plus"></span>
	</button>
	<div class="animenu container" id="soruform" style="display:none;">
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
					
					echo '<meta http-equiv="refresh" content="0;url=sorucevap">';

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
					<span class="input-group-text"><i class="fas fa-university"></i></span>
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
	</div>
<?
	
	footer();

?>