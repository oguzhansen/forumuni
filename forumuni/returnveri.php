<?php
	
	require_once "core.php";
	
	$uname = $_SESSION['sec-username']; 
	$user_id = $rowu['id'];
	
	$db = new PDO("mysql:host=localhost;dbname=xcuforum_unifor;charset=utf8","xcuforum","X[ZT{Kj9");
	
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
	<div id="uniara">
		<? 
		if($count == 0)
		{
			echo "<br/><div class='alert alert-danger'>Bu üniversite kayıtlarımızda bulunamadı.</div>";
		}
		
		else
		{
			foreach($control as $val){?>
			
				<div class="tweet-wrap" >
					<div class="tweet-header">
						<a class="card-subtitle mb-2 text-muted" href="universitelerc.php?uniid=<? echo $val["universite_id"]; ?>" >
							<img src="<? echo $val["image"] ?>" alt="" class="avator">
						</a>
						<div class="tweet-header-info">
							<a href="universitelerc.php?uniid=<? echo $val["universite_id"]; ?>" ><? echo $val["name"];?></a>
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
				
				$unigetir = $db->query("select * from universite where universite_id = '".$val["uni"]."'");
				$unigetir = mysqli_fetch_assoc($unigetir);
				
				?>
			
				<div class="tweet-wrap" >
					<div class="tweet-header">
						<a class="card-subtitle mb-2" href="
						<?
						
						if($val["username"] == $uname)
						{
							echo "myprofile.php";
						}
						
						else
						{
							echo "user.php?username=",$val["username"];
						}
						?>
						" >
							<img src="<? echo $val["avatar"] ?>" alt="" class="avator">
						</a>
						<div class="tweet-header-info">
							<a class="card-subtitle mb-2" href="
						<?
						
						if($val["username"] == $uname)
						{
							echo "myprofile.php";
						}
						
						else
						{
							echo "user.php?username=",$val["username"];
						}
						?>
						" ><? echo $val["username"];?></a><span>. 
							<a href="universitelerc.php?uniid=<? echo $val["uni"] ?>" > <? echo $unigetir["name"]; ?></a> </span>
							<p><? echo $val["adsoyad"]; ?></p>
						</div>
					</div>
				</div>
			
			<?
			}
		}

		?>
	</div>