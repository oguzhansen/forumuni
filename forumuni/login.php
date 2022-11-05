<?php
	include "core.php";
	head();

	$error = 0;
?>
	<div class="girisyap">
		<h5 class="baslikgiris" style="text-align:center;">Giriş Yap</h5>
		<?php
			if (isset($_POST['signin'])) {
				$username = mysqli_real_escape_string($connect, $_POST['username']);
				$password = hash('sha256', $_POST['password']);
				$check    = mysqli_query($connect, "SELECT username, password FROM `users` WHERE `username`='$username' AND password='$password'");
				if (mysqli_num_rows($check) > 0) {
					$_SESSION['sec-username'] = $username;
					echo '<meta http-equiv="refresh" content="0;url=index.php">';
				} else {
					echo '
					<div class="alert alert-danger">
						<i class="fas fa-exclamation-circle"></i> Girilen <strong>Kullanıcı Adı</strong> veya <strong>Şifre</strong> Yanlış.
					</div>';
					$error = 1;
				}
			}
		?> 
		<form action="" method="post">
			<div class="input-group mb-3 needs-validation <?php if ($error == 1) { echo 'is-invalid'; } ?>">
				<span class="input-group-text"><i class="fas fa-user"></i></span>
				<input type="username" name="username" class="form-control" placeholder="Kullanıcı Adı" <?php if ($error == 1) { echo 'autofocus'; } ?> required>
			</div>
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-key"></i></span>
				<input type="password" name="password" class="form-control" placeholder="Şifre" required>
			</div>
			<div class="input-group mb-3 needs-validation">
				<input class="form-check-input" type="checkbox" value="" name="benihat" id="benihat">
				<label class="form-check-label" for="benihat">  &nbsp;&nbsp; Beni Hatırla</label>
			</div>
			<button type="submit" name="signin" class="btn btn-primary col-12"><i class="fas fa-sign-in-alt"></i>&nbsp;Giriş Yap</button>
		</form>
		<p class="kayitgonder">Hesabın yok mu? Hemen <span style="cursor:pointer; color:#005fbf;" onclick="uyelik()" id="uyelik">Üye ol</span></p>
	</div>
	
	<div class="kayitol">
		<?php
			if (isset($_POST['register'])) {
				$adsoyad = $_POST['adsoyad'];
				$username = $_POST['username'];
				$password = hash('sha256', $_POST['password']);
				$email    = $_POST['email'];
				$uni    = $_POST['MemberUni'];
				$fak    = $_POST['MemberFak'];
				$bolum    = $_POST['MemberBolum'];
				$captcha  = '';
				$zaman = time();
				if (isset($_POST['g-recaptcha-response'])) {
					$captcha = $_POST['g-recaptcha-response'];
				}
				if ($captcha) {
					$url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($row['gcaptcha_secretkey']) . '&response=' . urlencode($captcha);
					$response     = file_get_contents($url);
					$responseKeys = json_decode($response, true);
					if ($responseKeys["success"]) {
						
						$sql = mysqli_query($connect, "SELECT username FROM `users` WHERE username='$username'");
						if (mysqli_num_rows($sql) > 0) {
							echo '<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Üzgünüm, Bu Kullanıcı Adı Zaten Var.</div>';
						} else {
							
							$sql2 = mysqli_query($connect, "SELECT email FROM `users` WHERE email='$email'");
							if (mysqli_num_rows($sql2) > 0) {
								echo '<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Üzgünüm, Bu E-Posta Zaten Var.</div>';
							} else {
								if(!isset($_POST['unilidegil']))
								{
									if($uni == 0 || $fak == 0 || $bolum == 0)
									{
										echo "<div class='alert alert-warning'>Üniversite seçimi yapmadınız.</div>";
									}
									
									else{
										$insert  = mysqli_query($connect, "INSERT INTO `users` (adsoyad, username, password, email, uni, uni_fakulte, uni_bolum, kayit_tarih) VALUES ('$adsoyad','$username', '$password', '$email','$uni', '$fak', '$bolum', '$zaman')");												
										$_SESSION['sec-username'] = $username;
										echo '<meta http-equiv="refresh" content="0;url=profilim">';
									}
								}
								else{
									$insert  = mysqli_query($connect, "INSERT INTO `users` (adsoyad,username, password, email, kayit_tarih) VALUES ('$adsoyad','$username', '$password', '$email', '$zaman')");											
									$_SESSION['sec-username'] = $username;
									echo '<meta http-equiv="refresh" content="0;url=profilim">';
								}
							}
						}
					}
				}
			}
			?>
		<form action="" method="post">
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-signature"></i></span>
				<input type="adsoyad" name="adsoyad" class="form-control" placeholder="Adı Soyadı" required>
			</div>
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-user"></i></span>
				<input type="username" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
			</div>
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-envelope"></i></span>
				<input type="email" name="email" class="form-control" placeholder="E-Posta" required>
			</div>
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-ban"></i></span>&nbsp;&nbsp;
				<input class="form-check-input" type="checkbox" value="" name="unilidegil" id="unilidegil">
				<label class="form-check-label" for="unilidegil">  &nbsp;&nbsp;Üniversiteli Değilim</label>
			</div>
			<div id="unidegil">
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
				<div class="input-group mb-3 needs-validation">
					<span class="input-group-text"><i class="fas fa-university"></i></span>
					<select  class="form-select" name="MemberFak" id="MemberFak">
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
			
			<div class="input-group mb-3 needs-validation">
				<span class="input-group-text"><i class="fas fa-key"></i></span>
				<input type="password" name="password" class="form-control" placeholder="Şifre" required>
			</div>
				<center><div class="g-recaptcha" data-sitekey="<?php echo $row['gcaptcha_sitekey']; ?>">
			</div>
			</center>
			<br/>

			<button type="submit" name="register" class="btn btn-primary col-12"><i class="fas fa-sign-in-alt"></i>&nbsp;Kayıt Ol</button>
		</form>
		<p class="kayitgonder">Bir hesabın var mı? <span style="cursor:pointer; color:#005fbf;" type="button" onclick="giris()" id="giris">Giriş yap</span></p>
	</div>
	</div>
	
<?php
footer();
?>