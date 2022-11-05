<?php
	require_once "core.php";

	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id'];

	$db = new PDO("mysql:host=localhost;dbname=xcuforum_unifor;charset=utf8","xcuforum","X[ZT{Kj9");

	$option = $_GET["option"];

	switch($option)
	{
		
		/**
		 * KEŞFET
		 */

		case 'kesfet':
	
				$value = $_POST['value'];
				
				$control = $db->query("select * from universite WHERE name LIKE '$value%'");
				
				$controluser = $db->query("select * from users WHERE username LIKE '$value%'");
				
				$countuser = $controluser->rowCount();
				
				$count = $control->rowCount();
				?>  
				<ul class="nav nav-pills nav-fill">
					<li role="button" class="nav-item" id="" onclick="uniara()">
						<a class="nav-link active" id="uniarabtn">Üniversiteler</a>
					</li>
					<li role="button" class="nav-item" id="" onclick="userara()">
						<a class="nav-link" id="userarabtn" >Kullanıcılar</a>
					</li>
				</ul>
					<br/>
				<div id="uniara">
					<? 
					if($count == 0)
					{
						echo "<br/><div class='alert alert-danger'>Bu üniversite kayıtlarımızda bulunamadı.</div>";
					}
					
					else
					{
						foreach($control as $val){?>
						
						<div class="card mb-3 gon" onclick="location.href='universitelerc.php?uniid=<? echo $val['universite_id']; ?>'">
							<div class="card-body">
								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="universitelerc.php?uniid=<? echo $val["universite_id"]; ?>" >
											<img src="<? echo $val["image"]; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2">
											<a class="card-subtitle mb-2" style="color:black;" href="universitelerc.php?uniid=<? echo $val["universite_id"]; ?>" ><? echo $val["name"];?> </a> 
										</p>	
									</div>
								</div>
							</div>
						</div>
						
						<?
						}
					}
					?>
				</div>
				
				<div id="userara" style="display:none;">
				
					<?
					if($countuser == 0)
					{
						echo "<br/><div class='alert alert-danger'>Bu kullanıcı kayıtlarımızda bulunamadı.</div>";
					}
					
					else
					{
						foreach($controluser as $val){
							
							$unideger = $val['uni'];

							?>

							<div class="card mb-3 gon" onclick="location.href='<? if($val['username'] == $uname) { echo 'profilim'; } else { echo $val['username']; } ?>'">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<div class="d-flex flex-row align-items-center">
											<a class="card-subtitle mb-2 text-muted" href="<? if($val["username"] == $uname) { echo "profilim"; } else { echo $val["username"]; } ?>" >
												<img src="<? echo $val["avatar"]; ?>" alt="avatar" style="border-radius:100px;" width="50" height="50" />
											</a>
											<p class="small mb-0 ms-3" style="margin-top:-7px;">
												<small style="font-size:14px; font-weight:500;"><? echo $val["adsoyad"]; ?>
													<a class="card-subtitle mb-2 text-muted" style="color:black;" href="<? if($val["username"] == $uname) { echo "profilim"; } else { echo $val["username"]; } ?> " ><? echo "@",$val["username"];?></a> 
												</small>
												<br/>
												<small>
												<?
												
												$unicon = $db->query("select * from universite WHERE universite_id = '$unideger'")->fetchAll(PDO::FETCH_ASSOC);
												
												foreach($unicon as $items => $value){
													?>
														<a class="text-muted" href="universitelerc.php?uniid=<? echo $val["uni"] ?>" ><? echo $value["name"];?></a> 
													<?
												}
												
												?>
												</small>
											</p>
										</div>
									</div>
								</div>
							</div>
						
						<?
						}
					}
			
					?>
				</div>
				<?

		break;



		/**
		 * FAKÜLTE LİSTE
		 */

		case 'fakulteliste':

			$uniid = $_POST['uni'];
			$ekle = "<option value='0'>Fakülte Seç</option>";
			$uni = $db->query("SELECT * FROM universite_fakulte where universite_id = '".$uniid."' order by name")->fetchAll(PDO::FETCH_ASSOC);
			foreach($uni as $items => $value){
				echo '<option value="'.$value['fakulte_id'].'">'.$value['name'].'</option>';
			}
		break;



		/**
		 * REZLE
		 */

		case 'rezle':
		
			$soruid = $_POST['soru'];
    
			$zaman = time();
		
			$reza = $db->query("select rez_id from rezler where soru_id = '$soruid'");
			$rezcount = $reza->rowCount();
		
			$rezus = $db->query("select * from rezler where soru_id = '$soruid' and user_id = '$user_id'");
			$rezuscount = $rezus->rowCount();
		
			$rez = $db->query("select * from rezler")->fetchAll(PDO::FETCH_ASSOC);
			
			if($rezuscount > 0)
			{
				$soru = $db->query("DELETE FROM rezler where soru_id = '$soruid' and user_id = '$user_id'")->fetchAll(PDO::FETCH_ASSOC);
				echo "<span style='color:black;'>#Rezle ", $rezcount-1,"</span>";

				$soru = $db->query("DELETE FROM bildirimler where soru_id = '$soruid' and kime_user = '$user_id'")->fetchAll(PDO::FETCH_ASSOC);

			}
			else
			{
				$soru = $db->query("INSERT INTO rezler(soru_id,user_id,rez_tarih) VALUES('$soruid','$user_id','$zaman')")->fetchAll(PDO::FETCH_ASSOC);
				echo "<span style='color:green;'>#Rezle ", $rezcount+1,"</span>";
			}

		break;



		/**
		 * BİLDİRİM OKU
		 */

		case 'bildoku':
		
			$bildid = $_POST['bild'];
			$bild = $db->query("UPDATE bildirimler SET okundu = '1' where bildirim_id = '$bildid'")->fetchAll(PDO::FETCH_ASSOC);

		break;


		/**
		 * YANITLA
		 */

		case 'yanitla':
			
			$cevapid = $_POST["cevap"];

			$users = $db->query("SELECT * FROM users WHERE id in(SELECT id FROM cevaplar WHERE cevap_id = '".$cevapid."')")->fetchAll(PDO::FETCH_ASSOC);
			foreach($users as $items => $value){
			?>

			<form action="" method="POST">
				<div class="cevapla" style="z-index:1;bottom:60px;">
						<i class="iptalyanit" onclick="yanitlakapat()"> İptal Et</i>
						<br/><textarea style="padding-top:5px; max-height:60px;" class="form-control" id="cevap" placeholder="<? echo $value["username"]; ?>'e yanıt veriyorsun" name="cevap" rows="3" required></textarea>
					<input type="hidden" value="<? echo $value["id"]; ?>" name="etiketlenen" />
					<input type="hidden" value="<? echo $cevapid; ?>" name="cevapid" />
					<button type="submit" class="btn btn-primary px-2 ms-3" id="cevapbtn" name="cevapgonderetkt">Cevapla</button>
				</div><br/><br/>
			</form>
			
			<?
			}
		break;


		/**
		 * YANITLA KAPAT
		 */

		case 'yanitlakapat':
			?>

<form action="" method="POST">
			<div class="cevapla" style="z-index:1;"><br/>
				<textarea style="max-height:60px;" class="form-control" id="cevap" placeholder="Soruyu Cevapla" name="cevap" rows="3" required></textarea>
				<button type="submit" class="btn btn-primary px-2 ms-3" id="cevapbtn" name="cevapgonder">Cevapla</button>
			</div><br/><br/>
		</form>
			
			<?
		break;


		/**
		 * ANA SAYFA FİLTER
		 */

		case 'anasayfafiltre':
			$filtre = $_POST['filtre'];

			$usera = mysqli_query($connect, "select * from users where username = '$uname'");
			$usera = mysqli_fetch_array($usera);

			$getir = mysqli_query($connect, "select * from sorular order by soru_id desc");

			$unigore = mysqli_query($connect,"select * from sorular where kat_id = '2' and uni_id = '".$usera["uni"]."' order by soru_id desc");
				
			$fakgore = mysqli_query($connect,"select * from sorular where kat_id = '3' and fak_id = '".$usera["uni_fakulte"]."' order by soru_id desc");

			$bolgore = mysqli_query($connect,"select * from sorular where kat_id = '4' and bol_id = '".$usera["uni_bolum"]."' order by soru_id desc");

			switch($filtre)
			{
				case '1':
					?>
					<div id="enyeni" class="row col-6 cardmob">	
						<?
							while($sorus = $getir->fetch_array())
							{
								$usern = mysqli_query($connect, "select * from users where id = '".$sorus["id"]."'");
								$usernam = mysqli_fetch_array($usern);
								
								$unigetir = mysqli_query($connect, "select * from universite where universite_id = '".$sorus["uni_id"]."'");
								$unigetir1 = mysqli_fetch_array($unigetir);
								
								$fakgetir = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$sorus["fak_id"]."'");
								$fakgetir1 = mysqli_fetch_array($fakgetir);
								
								$bolgetir = mysqli_query($connect, "select * from bolumler where bolum_id = '".$sorus["bol_id"]."'");
								$bolgetir = mysqli_fetch_array($bolgetir);
								
								$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$sorus["soru_id"]."'");
								$cevap = mysqli_fetch_array($cevap);

								$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$sorus["soru_id"]."'");
								$rez = mysqli_fetch_array($rez);

								$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$sorus["soru_id"]."' and user_id = '$user_id'");
								$rezs = mysqli_fetch_array($rezs);
						?>
						
						<div class="card mb-3 gon">
							<div class="card-body">
								<div onclick="location.href='soru.php?soruid=<? echo $sorus['soru_id']; ?>'">
									<p class="soruindex"><? echo substr($sorus["soru"],0,75); ?>
									<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;"> 
									<?	
										if($sorus["kat_id"] == 1){ echo " Genel Sorular"; } 
										if($sorus["kat_id"] == 2){ echo " ",$unigetir1["name"]; } 
										if($sorus["kat_id"] == 3){ echo " ",$fakgetir1["name"]," | ",substr($unigetir1["name"],0,15),"..."; } 
										if($sorus["kat_id"] == 4){ echo " ",$bolgetir["bolum_adi"]; } 
										if($sorus["kat_id"] == 5){ echo " Yurtlar Hakkında"; } 
									?>
									</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>
								</div>
								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" >
											<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" ><? echo $usernam["username"];?> </a> 
											<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($sorus["soru_tarih"]); ?></small>
										</p>
									</div>
									<? if($logged == 'Yes' && $sorus["id"] != $user_id){ ?>
									<div class="d-flex flex-row align-items-center">
										<div id="rezbtn">
											<button style="font-size:15px; float:left; <? if($sorus["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $sorus["soru_id"]; ?>">
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

						<br/>
					
					<? 
					
					$getir = mysqli_query($connect, "select count(*) as soru_id from sorular");
					
					
					if($getir == 0){ ?>
					<center>
						<p style="width:97%;" class="alert alert-danger">Hiç soru yok hemen <a href="profilim">ekle.</p><br/>
					</center>
					<? } ?>
				</div>

					<?
				break;
				
				case '2': ?>
					
					<div class="row col-6 cardmob" id="unigore">	
						<?
							while($sorus = $unigore->fetch_array())
							{
								$usern = mysqli_query($connect, "select * from users where id = '".$sorus["id"]."'");
								$usernam = mysqli_fetch_array($usern);
								
								$unigetir = mysqli_query($connect, "select * from universite where universite_id = '".$usernam["uni"]."'");
								$unigetir1 = mysqli_fetch_array($unigetir);
								
								$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$sorus["soru_id"]."'");
								$rez = mysqli_fetch_array($rez);

								$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$sorus["soru_id"]."' and user_id = '$user_id'");
								$rezs = mysqli_fetch_array($rezs);

								$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$sorus["soru_id"]."'");
								$cevap = mysqli_fetch_array($cevap);
						?>
						<div class="card mb-3 gon">
							<div class="card-body">
								<div onclick="location.href='soru.php?soruid=<? echo $sorus['soru_id']; ?>'">
									<p class="soruindex"><? echo substr($sorus["soru"],0,75); ?>
									<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;"> 
									<? echo " ",$unigetir1["name"];?>
										</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
									</p>
								</div>
							
								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="<? if($usernam["username"] == $uname){ echo "profilimp"; } else { echo $usernam["username"]; }?>" >
											<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" ><? echo $usernam["username"];?> </a> 
											<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($sorus["soru_tarih"]); ?></small>
										</p>
									</div>
									<? if($logged == 'Yes' && $sorus["id"] != $user_id){ ?>
									<div class="d-flex flex-row align-items-center">
										<div id="rezbtn">
											<button style="font-size:15px; float:left; <? if($sorus["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $sorus["soru_id"]; ?>">
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
						<? } ?><br/>
						<?
						
						$unigore = mysqli_query($connect, "select count(*) as soru_id from sorular where uni_id in(select uni from users where username = '$uname')");
						$unigore = mysqli_fetch_array($unigore);
						
						if($unigore["soru_id"] == 0){ ?>
						<center>
							<p style="width:97%;" class="alert alert-danger">Üniversitene ait hiç soru yok hemen <a href="profilim">ekle.</a></p><br/>
						</center>
						<? } else { ?>
						<center>
							<p style="width:97%;" class="alert alert-danger">Tüm soruları gördün biraz dinlen.</p><br/>
						</center>
						<? } ?>
					</div>
				<?
				break;

				case '3':
					?>
					
					<div class="row col-6 cardmob" id="fakgore">	
						<?
							while($sorus = $fakgore->fetch_array())
							{
								$usern = mysqli_query($connect, "select * from users where id = '".$sorus["id"]."'");
								$usernam = mysqli_fetch_array($usern);
								
								$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$sorus["soru_id"]."'");
								$rez = mysqli_fetch_array($rez);

								$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$sorus["soru_id"]."' and user_id = '$user_id'");
								$rezs = mysqli_fetch_array($rezs);

								$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$usernam["uni"]."'");
								$uniget = mysqli_fetch_array($uniget);
								
								$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$usernam["uni_fakulte"]."'");
								$fakget = mysqli_fetch_array($fakget);
								
								$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$sorus["soru_id"]."'");
								$cevap = mysqli_fetch_array($cevap);
						?>
						<div class="card mb-3 gon">
							<div class="card-body" onclick="location.href='soru.php?soruid=<? echo $sorus['soru_id']; ?>'">
								<div onclick="location.href='soru.php?soruid=<? echo $sorus['soru_id']; ?>'">
									<p class="soruindex"><? echo substr($sorus["soru"],0,75); ?>
										<br/>
										<div class="text-muted" style="font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;">
											<a class="text-muted" href="universitelerc.php?uniid=<? echo $usernam["uni"] ?>" > <? echo $fakget["name"]," / ",$uniget["name"]; ?></a> 
										</div>&nbsp;
										<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span>
									</p>	
								</div>

								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" >
											<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" ><? echo $usernam["username"];?> </a> 
											<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($sorus["soru_tarih"]); ?></small>
										</p>
									</div>
									<? if($logged == 'Yes' && $sorus["id"] != $user_id){ ?>
									<div class="d-flex flex-row align-items-center">
										<div id="rezbtn">
											<button style="font-size:15px; float:left; <? if($sorus["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $sorus["soru_id"]; ?>">
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
						<? } ?><br/>
						<?
						
						$fakgore = mysqli_query($connect, "select count(*) as soru_id from sorular where fak_id in(select uni_fakulte from users where username = '$uname')");
						$fakgore = mysqli_fetch_assoc($fakgore);
						
						if($fakgore["soru_id"] == 0){ ?>
						<center>
							<p style="width:97%;" class="alert alert-danger">Fakültene ait hiç soru yok hemen <a href="profilim">ekle.</a></p><br/>
						</center>
						<? } else { ?>
						<center>
							<p style="width:97%;" class="alert alert-danger">Tüm soruları gördün biraz dinlen.</p><br/>
						</center>
						<? } ?>
					</div>
					
					<?
				break;

				case '4':
					?>
					
					<div class="row col-6 cardmob" id="bolgore">	
						<?
							while($sorus = $bolgore->fetch_array())
							{
								$usern = mysqli_query($connect, "select * from users where id = '".$sorus["id"]."'");
								$usernam = mysqli_fetch_array($usern);
								
								$bolgetir = mysqli_query($connect, "select * from bolumler where bolum_id = '".$usernam["uni_bolum"]."'");
								$bolgetir = mysqli_fetch_array($bolgetir);
								
								$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$sorus["soru_id"]."'");
								$rez = mysqli_fetch_array($rez);

								$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$sorus["soru_id"]."' and user_id = '$user_id'");
								$rezs = mysqli_fetch_array($rezs);

								$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$sorus["soru_id"]."'");
								$cevap = mysqli_fetch_array($cevap);
						?>
						<div class="card mb-3 gon">
							<div class="card-body" onclick="location.href='soru.php?soruid=<? echo $sorus['soru_id']; ?>'">
								<p class="soruindex"><? echo substr($sorus["soru"],0,75); ?>
								<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;"> 
								<?	
									if($sorus["kat_id"] == 1){ echo " Genel Sorular"; } 
									if($sorus["kat_id"] == 2){ echo " ",$unigetir1["name"]; } 
									if($sorus["kat_id"] == 3){ echo " ",$unigetir1["name"]; } 
									if($sorus["kat_id"] == 4){ echo " ",$bolgetir["bolum_adi"]; } 
									if($sorus["kat_id"] == 5){ echo " Yurtlar Hakkında"; } 
								?>
									</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
								</p>

								<div class="d-flex justify-content-between">
									<div class="d-flex flex-row align-items-center">
										<a class="card-subtitle mb-2 text-muted" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" >
											<img src="<? echo $usernam["avatar"] ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
										</a>
										<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($usernam["username"] == $uname){ echo "profilim"; } else { echo $usernam["username"]; }?>" ><? echo $usernam["username"];?> </a> 
											<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($sorus["soru_tarih"]); ?></small>
										</p>
									</div>
									<? if($logged == 'Yes' && $sorus["id"] != $user_id){ ?>
									<div class="d-flex flex-row align-items-center">
										<div id="rezbtn">
											<button style="font-size:15px; float:left; <? if($sorus["soru_id"] == $rezs['soru_id'] && $user_id == $rezs['user_id']){ echo 'color:green;';}else{ echo ''; } ?>" class="btn rezle" id="rezle" type="button" title="<? echo $sorus["soru_id"]; ?>">
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
						<? } ?><br/>
						<?
						
						$bolgore = mysqli_query($connect, "select count(*) as soru_id from sorular where bol_id in(select uni_bolum from users where username = '$uname')");
						$bolgore = mysqli_fetch_assoc($bolgore);
						
						if($bolgore["soru_id"] == 0){ ?>
						<center>	
							<p style="width:97%;" class="alert alert-danger">Bölümüne ait hiç soru yok hemen <a href="profilim">ekle.</a></p><br/>
						</center>
						<? } else { ?>
						<center>
							<p style="width:97%;" class="alert alert-danger">Tüm soruları gördün biraz dinlen.</p><br/>
						</center>
						<? } ?>
					</div>

					<?
				break;
			}
		break;

		/**
		 * SORUCEVAP KATEGORİ
		 */

		case 'sorucevkategori':
			$sorucevfilter = $_POST["sorucevfilter"];

			$usera = mysqli_query($connect, "select * from users where username = '$uname'");
			$usera = mysqli_fetch_array($usera);

			$genels = mysqli_query($connect,"select * from sorular where kat_id = '1' order by soru_id desc");
	
			$unis = mysqli_query($connect,"select * from sorular where kat_id = '2' order by soru_id desc");
			
			$faks = mysqli_query($connect,"select * from sorular where kat_id = '3' order by soru_id desc");
			
			$bols = mysqli_query($connect,"select * from sorular where kat_id = '4' order by soru_id desc");
			
			$yurtlars = mysqli_query($connect,"select * from sorular where kat_id = '5' order by soru_id desc");

			/* kendime göre */
	
			$uniuser = mysqli_query($connect,"select * from sorular where kat_id = '2' and uni_id = '".$usera["uni"]."' order by soru_id desc");
			
			$fakuser = mysqli_query($connect,"select * from sorular where kat_id = '3' and fak_id = '".$usera["uni_fakulte"]."' order by soru_id desc");
			
			$boluser = mysqli_query($connect,"select * from sorular where kat_id = '4' and bol_id = '".$usera["uni_bolum"]."' order by soru_id desc");


			switch($sorucevfilter)
			{
				case '1':
					
					?>

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

					<?

				break;

				case '2':
					
					if($uname != "" && $usera["uni"] != 0){ ?>
					<center>
						<span style="margin-bottom:18px;" id="unim"><input class="form-check-input" type="checkbox" name="unima" id="unima"><label for="unima">&nbsp;Üniversitem Hakkında<label><br/></span>
					</center>
					<br/>
					<? } ?>
					<!--- Üniversiteler Hakkında --->
					<div id="unis" class="row col-6 cardmob">
						<span id="uninone">
								<? while ($cikti = $unis->fetch_array()){ 
								
								$unigetir1 = mysqli_query($connect, "select * from universite where universite_id = '".$cikti["uni_id"]."'");
								$unigetir1 = mysqli_fetch_array($unigetir1);

								$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
								$rez = mysqli_fetch_array($rez);

								$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
								$rezs = mysqli_fetch_array($rezs);
								
								$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
								$cikar = mysqli_fetch_array($cikar);
								
								$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
								$cevap = mysqli_fetch_array($cevap);
								
								?>		
								<div class="card mb-3 gon">
									<div class="card-body">
										<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
											<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
												<br/>
												<div class="text-muted" style="font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;">
													<a class="text-muted" href="universitelerc.php?uniid=<? echo $cikar["uni"] ?>" > <? echo $unigetir1["name"]; ?></a> 
												</div>&nbsp;
												<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span>
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
						</span>
						<span id="uniuser"  style="display:none;">
								<?while ($cikti = $uniuser->fetch_array()){
									
									$unigetir1 = mysqli_query($connect, "select * from universite where universite_id = '".$cikti["uni_id"]."'");
									$unigetir1 = mysqli_fetch_array($unigetir1);

									$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
									$rez = mysqli_fetch_array($rez);

									$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
									$rezs = mysqli_fetch_array($rezs);
									
									$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
									$cikar = mysqli_fetch_array($cikar);
									
									$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
									$cevap = mysqli_fetch_array($cevap);
									
									?>
									<div class="card mb-3 gon">
										<div class="card-body">
											<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
												<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
													<br/>
													<div class="text-muted" style="font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;">
														<a class="text-muted" href="universitelerc.php?uniid=<? echo $cikar["uni"] ?>" > <? echo $unigetir1["name"]; ?></a> 
													</div>&nbsp;
													<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span>
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
						</span>
					</div>


					<?

				break;

				case '3':

						if($uname != "" && $usera["uni"] != 0){ ?>
							<center>
								<span style="margin-bottom:18px;" id="fakim"><input class="form-check-input" type="checkbox" name="fakima" id="fakima"><label for="fakima">&nbsp;Fakültem Hakkında<label><br/></span>
							</center>
							<br/>
						<? } ?>

						<!--- Fakülteler Hakkında --->
						<div id="faks" class="row col-6 cardmob">
							<span id="faknone">
									<? while ($cikti = $faks->fetch_array()){
									
									$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$cikti["fak_id"]."'");
									$fakget = mysqli_fetch_array($fakget);
									
									$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
									$rez = mysqli_fetch_array($rez);

									$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
									$rezs = mysqli_fetch_array($rezs);

									$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$cikti["uni_id"]."'");
									$uniget = mysqli_fetch_array($uniget);
									
									$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
									$cikar = mysqli_fetch_array($cikar);
									
									$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
									$cevap = mysqli_fetch_array($cevap);
									
									?>
										<div class="card mb-3 gon">
											<div class="card-body">
											<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
													<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
														<br/>
														<div class="text-muted" style="font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;">
															<a class="text-muted" href="universitelerc.php?uniid=<? echo $cikar["uni"] ?>" > <? echo $fakget["name"]," / ",$uniget["name"]; ?></a> 
														</div>&nbsp;
														<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span>
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
							</span>
							<span id="fakuser" style="display:none;">
									<? while ($cikti = $fakuser->fetch_array()){ 
									
									$uniget = mysqli_query($connect, "select * from universite where universite_id = '".$cikti["uni_id"]."'");
									$uniget = mysqli_fetch_array($uniget);
									
									$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
									$rez = mysqli_fetch_array($rez);

									$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
									$rezs = mysqli_fetch_array($rezs);

									$fakget = mysqli_query($connect, "select * from universite_fakulte where fakulte_id = '".$cikti["fak_id"]."'");
									$fakget = mysqli_fetch_array($fakget);
									
									$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
									$cikar = mysqli_fetch_array($cikar);
									
									$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
									$cevap = mysqli_fetch_array($cevap);
									
									?>
										<div class="card mb-3 gon">
											<div class="card-body">
												<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
													<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
														<br/>
														<div class="text-muted" style="font-size:13px; float:left; white-space: nowrap; overflow: hidden; max-width: 75%; text-overflow: ellipsis;">
															<a class="text-muted" href="universitelerc.php?uniid=<? echo $cikar["uni"] ?>" > <? echo $fakget["name"]," / ",$uniget["name"]; ?></a> 
														</div>&nbsp;
														<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span>
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
							</span>
						</div>

					<?
				
				break;

				case '4':

					if($uname != "" && $usera["uni"] != 0){ ?>
						<center>
							<span style="margin-bottom:18px;" id="bolim"><input class="form-check-input" type="checkbox" name="bolima" id="bolima"><label for="bolima">&nbsp;Bölümüm Hakkında<label><br/></span>
						</center>
						<br/>
					<? } ?>


						<!--- Bölümler Hakkında --->
						<div id="bols" class="row col-6 cardmob">
							<span id="bolnone">
									<? while ($cikti = $bols->fetch_array()){ 
									
									$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
									$cikar = mysqli_fetch_array($cikar);

									$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
									$rez = mysqli_fetch_array($rez);

									$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
									$rezs = mysqli_fetch_array($rezs);
									
									$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
									$cevap = mysqli_fetch_array($cevap);
									
									$bolgetir = mysqli_query($connect, "select * from bolumler where bolum_id = '".$cikti["bol_id"]."'");
									$bolgetir = mysqli_fetch_array($bolgetir);
									
									?>
										<div class="card mb-3 gon">
											<div class="card-body">
												<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
													<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
													<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;">
														<? echo $bolgetir["bolum_adi"]; ?> 
														</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
														</span>
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
							</span>
							<span id="boluser"  style="display:none;">
									<? while ($cikti = $boluser->fetch_array()){
										
										$cikar = mysqli_query($connect, "select * from users where id = '".$cikti["id"]."'");
										$cikar = mysqli_fetch_array($cikar);

										$rez = mysqli_query($connect, "select count(*) as rez_id from rezler where soru_id = '".$cikti["soru_id"]."'");
										$rez = mysqli_fetch_array($rez);

										$rezs = mysqli_query($connect, "select * from rezler where soru_id = '".$cikti["soru_id"]."' and user_id = '$user_id'");
										$rezs = mysqli_fetch_array($rezs);
										
										$cevap = mysqli_query($connect, "select count(*) as cevap_id from cevaplar where soru_id = '".$cikti["soru_id"]."'");
										$cevap = mysqli_fetch_array($cevap);
										
										$bolgetir = mysqli_query($connect, "select * from bolumler where bolum_id = '".$cikti["bol_id"]."'");
										$bolgetir = mysqli_fetch_array($bolgetir);
										
										?>
										<div class="card mb-3 gon">
											<div class="card-body">
												<div onclick="location.href='soru.php?soruid=<? echo $cikti['soru_id']; ?>'">
													<p class="soruindex"><? echo substr($cikti["soru"],0,75); ?>
													<br/><span class="text-muted" style="font-size:13px; font-weight:200; color:grey;">
														<? echo $bolgetir["bolum_adi"]; ?> 
														</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
														</span>
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
							</span>
						</div>

					<?
				
				break;

				case '5':

					?>

						<!--- Yurtlar Hakkında --->
						<div id="yurtlars" class="row col-6 cardmob">
							<div class="row">
								<? while ($cikti = $yurtlars->fetch_array()){ 

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
												Yurtlar Hakkında
												</span>&nbsp;<span style="border-radius:5px; border:1px solid green; color:green; font-size:11px;  padding:5px;"><? echo $cevap["cevap_id"]; ?> Cevap</span></p>	
												</span>
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

					<?
				
				break;
			}
		break;
	}

?>