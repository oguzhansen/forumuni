<?php
include "core.php";
head();
$uname = $_SESSION['sec-username']; 
$user_id = $rowu['id'];


	$usera = mysqli_query($connect, "select * from users where username = '$uname'");
	$usera = mysqli_fetch_array($usera);

	$getir = mysqli_query($connect, "select * from sorular order by soru_id desc");

?>
	<div style="margin:20 0 20 0px;" class="slider">
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  			<ol class="carousel-indicators">
    			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  			</ol>
  			<div class="carousel-inner">
    			<div class="carousel-item active">
      				<img class="d-block w-100" style="border-radius:15px;" height="200px" src="https://i4.hurimg.com/i/hurriyet/75/750x422/5f2af9482269a22c5c2958e2.jpg" alt="First slide">
				</div>
    			<div class="carousel-item">
      				<img class="d-block w-100" style="border-radius:15px;" height="200px" src="https://iasbh.tmgrup.com.tr/a97d17/650/344/0/0/731/383?u=https://isbh.tmgrup.com.tr/sbh/2021/09/19/universiteler-ne-zaman-acilacak-hangi-universiteler-ne-zaman-aciliyor-ve-dersler-ayin-kacinda-basliyor-1632038077272.jpg" alt="Second slide">
    			</div>
    			<div class="carousel-item">
      				<img class="d-block w-100" style="border-radius:15px;" height="200px" src="https://cdn.istanbul.edu.tr/FileHandler.ashx?f=ppjroz6Pjk2p_P0SKKsZxQ" alt="Third slide">
    			</div>
  			</div>
  			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    			<span class="sr-only">Previous</span>
  			</a>
  			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    			<span class="carousel-control-next-icon" aria-hidden="true"></span>
    			<span class="sr-only">Next</span>
  			</a>
		</div>
	</div>

		<? if($uname != "" && $usera["uni"] != 0){ ?>
			<center>
			<form action="" method="GET" style="margin-bottom:25px;">
				<select class="form-select form-select-sm selectde" style="border-radius:15px; padding:10px;" id="listsel" name="listsel" aria-label="Default select example">
					<option value="1" selected>En Yeniler</option>
					<option value="2">Üniversiteme Göre</option>
					<option value="3">Fakülteme Göre</option>
					<option value="4">Bölümüme Göre</option>
				</select>
			</form>
			</center>
		<? } ?>		
<div class="container">
	<div class="row">
		<? sidebar(); ?>
		<div class="allfilter">
				<div id="enyeni" class="col-6 cardmob">	
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
		</div>
		<? sidebar(); ?>
	</div>
</div>
</div>

<?php
footer();
?>