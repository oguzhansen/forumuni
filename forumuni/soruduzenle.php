<?php
include "core.php";
head();
if ($logged == 'No') {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit;
}

    $id = $_GET["soruid"];

    $uname = $_SESSION['sec-username']; 
    $user_id = $rowu['id'];

    $sorucek = mysqli_query($connect,"select * from sorular where soru_id = '$id'");
    $sorucek = mysqli_fetch_array($sorucek);

    $soru = $sorucek["soru"];

    if(isset($_POST["sorudzn"]))
    {
        mysqli_query($connect,"UPDATE sorular SET soru = '".$_POST['sorugelen']."' where soru_id = '$id'");
        
        echo '<meta http-equiv="refresh" content="0;url=profilim">';
    }

?>
	<form method="POST" class="border-0 form-control" style="position:relative;">
        <a href="profilim">İptal Et</a>
        <br/>
        <br/>
        <!-- Email input -->
        <span class="text-muted p-2 mx-auto">Soru:</span><br/>
        <div class="form-outline mb-4">
            <textarea class="form-control" id="sorugelen" name="sorugelen" rows="3" placeholder="Soru" required><? echo $soru;?></textarea>
        </div>   

        <!-- Submit button -->
        <button type="submit" style="position:absolute; right:10; top:3;" id="sorudzn" name="sorudzn" class="btn btn-primary btn-block"> Soruyu Kaydet</button>
    </form>

</div>
</div>
<?php

footer();
?>