<?php
include "core.php";
mysqli_set_charset($connect, "utf8mb4");
head();

	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id']; 
	
	$id = (int) $_GET['uniid'];

	if (empty($id)) {
		echo '<meta http-equiv="refresh" content="0; url=404.php">';
		exit;
	}

	$runq = mysqli_query($connect, "SELECT * FROM `universite` WHERE universite_id='$id'");
	if (!$runq) {
		echo '<meta http-equiv="refresh" content="0; url=404.php">';
		exit;
	}

	if(isset($_POST["gonder"]))
	{
		$zaman = time();
		$sql="insert into uni_comment(yorum,memnuniyet,id,universite_id,yorum_tarih) values('".$_POST["yorum"]."','".$_POST["mem"]."','$user_id','$id','$zaman')";
		$sonuc=mysqli_query($connect,$sql);
		if($sonuc)
		{

			$uniuser = mysqli_query($connect,"select * from users where uni = '$id'");

			while($unius = $uniuser->fetch_array())
			{
				$user = $unius["id"];
				mysqli_query($connect,"insert into bildirimler(bildirim_katid, kime_user, user_id, yorum, uni_id, bildirim_tarih) values('3', '$user','$user_id','".$_POST["yorum"]."', '$id','$zaman')");
				echo '<meta http-equiv="refresh" content="0.5;">';
			}
		}
	}

	$row = mysqli_fetch_assoc($runq);

	$sonuc = mysqli_query($connect,"select * from users where username = '$uname'");
					
	$univar = mysqli_fetch_array($sonuc);
	
	$sonuc6 = mysqli_query($connect,"select count(*) as id from uni_comment where id in(select id from users where username = '$uname')");
					
	$tek = mysqli_fetch_array($sonuc6);

	$rating     = mysqli_query($connect, "SELECT AVG(memnuniyet) FROM uni_comment where universite_id = '$id'");
	$ratingc = mysqli_fetch_array($rating);


	?>
	
	<div class="container mt-5">
		<center><div class="rounded-circle" style="width:150px;height:150px;overflow:hidden;margin:0;">
			<img src="<? echo $row["image"]; ?>" style="display:block;width:150px;" />
		</div></center>
		<div class="text-center mt-3">
			<h5 class="mt-2 mb-0"><? echo $row["name"]; ?></h5><br/>
				
				<svg style="display:none;">
					<defs>
						<symbol id="fivestars">
						<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd"/>
						<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(24)"/>
						<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(48)"/>
						<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(72)"/>
						<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(96)"/>
						</symbol>
					</defs>
				</svg>
				<p style="float:left;">Memnuniyet: &nbsp;</p>
				<div class="rating">
					<progress class="rating-bg" value="<? echo $ratingc['AVG(memnuniyet)']; ?>" max="5"></progress>
					<svg><use xlink:href="#fivestars"/></svg>
				</div>

			<br/>
			<ul class="nav nav-pills nav-fill">
				<li class="nav-item">
					<? if($tek[0] == 0 && $univar["uni"] == $id){ ?>
						<button class="btn btn-outline-primary px-4 m-3" onclick="degerlendir()">Değerlendirme Gir</button>
					<? } ?>
					<span class="nav-link active" >Değerlendirmeler</span><br/>
				</li>
			</ul>
		</div> 
	</div>
	<div class="alert alert-info" id="deger">
		<i style="float:right;" class="fas fa-close closedege" role="button" onclick="closedege()"></i><br/><br/>
		<h5><? echo $row["name"]; ?></h5><br/>
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
<div class="container">
	<div class="row">
		<? sidebar(); 
		
		$q     = mysqli_query($connect, "SELECT * FROM uni_comment WHERE universite_id='$row[universite_id]' order by yorum_id desc");
		$count = mysqli_num_rows($q);
			if ($count <= 0) {
				echo '<div class="alert alert-info col-6 cardmob">Henüz değerlendirme yok.</div>';
				sidebar();
			} else {
		?>
		
		<div class="row col-6 cardmob">
			<?
				while ($comment = mysqli_fetch_array($q)) {
					$aauthor = $comment['id'];
		
					$querych = mysqli_query($connect, "SELECT * FROM `users` WHERE id='$aauthor' LIMIT 1");
					if (mysqli_num_rows($querych) > 0) {
						$rowch = mysqli_fetch_assoc($querych);
						
						$aavatar = $rowch['avatar'];
						$aauthor = $rowch['username'];
						if ($rowch['role'] == 'Admin') {
							$arole = '<span class="badge bg-danger">Admin</span>';
						} elseif ($redprv['rolq'] == 'Editor') {
							$arole = '<span class="badge bg-warning">Yazar</span>';
						} else {
							$arole = '<span class="badge bg-info">Üye</span>';
						}
					}
					
					$sonuc = mysqli_query($connect,"select name from universite_fakulte where fakulte_id in(select uni_fakulte from users where username = '$aauthor')");
							
					$univar = mysqli_fetch_array($sonuc);
			?>
			
			<div class="card mb-3 gon">
				<div class="card-body">
					<p class="soruindexa"><? echo $comment["yorum"]; ?>
					<div class="d-flex justify-content-between">
						<div class="d-flex flex-row align-items-center">
							<a class="card-subtitle mb-2 text-muted" href="<? if($aauthor == $uname){ echo "profilim"; } else { echo $aauthor; }?>" >
								<img src="<? echo $aavatar ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
							</a>
							<p class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black;" href="<? if($aauthor == $uname){ echo "profilim"; } else { echo $aauthor; }?>" ><? echo $aauthor;?> </a> 
								<small style="font-size:12px; font-weight:200;" class="text-muted"> &nbsp;<? echo " ",zaman($comment["yorum_tarih"]); ?></small>
							</p>
						</div>
					</div>
				</div>
				<div class="drpdown" style="top:10; right:5px; position:absolute; float:right;">
					<a href="#" class="nav-link link-dark" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="soru.php?soruid=<? echo $comment["yorum"]; ?>"> Şikayet Et</a></li>
					</ul>
				</div>
			</div>

			<? } ?> 
		</div>
	<? sidebar();} ?>
		
	</div>
</div>
	
	<?php footer(); ?>