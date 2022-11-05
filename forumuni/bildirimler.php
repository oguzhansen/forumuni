<?php
include "core.php";
head();
if ($logged == 'No') {
    echo '<meta http-equiv="refresh" content="0;url=login.php">';
    exit;
}
    $uname = $_SESSION['sec-username']; 
    $user_id = $rowu['id'];

    $bild = mysqli_query($connect,"SELECT * FROM bildirimler where kime_user = '$user_id' order by bildirim_id desc");
?>
    <div class="row bildpanel" style="margin-top:20px;padding:3px;"> 
        <?  while($bilda = $bild->fetch_array())
            { 
                $bildus = mysqli_query($connect,"SELECT * FROM users where id = '".$bilda["user_id"]."'");
                $bildus = mysqli_fetch_array($bildus);

                $bildkat = mysqli_query($connect,"SELECT * FROM bildirim_kat where bildirim_katid = '".$bilda["bildirim_katid"]."'");
                $bildkat = mysqli_fetch_array($bildkat);

                $bildsor = mysqli_query($connect,"SELECT * FROM sorular where soru_id = '".$bilda["soru_id"]."'");
                $bildsor = mysqli_fetch_array($bildsor);

                $bildun = mysqli_query($connect,"SELECT * FROM universite where universite_id = '".$bilda["uni_id"]."'");
                $bildun = mysqli_fetch_array($bildun);
                
                $bildusun = mysqli_query($connect,"SELECT * FROM users where id = '$user_id'");
                $bildusun = mysqli_fetch_array($bildusun);

                $bildunic = mysqli_query($connect,"SELECT * FROM uni_comment where universite_id = '".$bilda["uni_id"]."' and id = '".$bilda["user_id"]."'");
                $bildunic = mysqli_fetch_array($bildunic);
            if($bilda["user_id"] != $user_id){
                ?>
                <div class="card mb-3 bildarka" onclick="oku()" title="<? echo $bilda["bildirim_id"]; ?>" style="<? if($bilda['okundu'] == 0){ echo 'background:white;'; } else { echo 'background:#ededed;'; } ?>">
                    <div class="card-body" onclick="location.href='<? if($bildkat['bildirim_katid'] == 2 || $bildkat['bildirim_katid'] == 4){ ?>soru.php?soruid=<? echo $bilda['soru_id']; } else if($bildkat['bildirim_katid'] == 3) { ?>universitelerc.php?uniid=<? echo $bilda['uni_id']; }?>'">
                        <p class="soruindexbild">
                            <a style="margin-right:8px;" class="card-subtitle mb-2 text-muted" href="<? echo $bildus["username"];?>" >
                                <img src="<? echo $bildus["avatar"]; ?>" alt="avatar" style="border-radius:100px;" width="30" height="30" />
                            </a>
                            <p style="padding:4px;" class="small mb-0 ms-2"><a class="card-subtitle mb-2" style="color:black; font-weight:600;" href="<? echo $bildus["username"]; ?>" >
                                <? echo $bildus["username"];?> 
                            </a>
                        <?  if($user_id != $bildsor["id"]){
                                
                                echo " ",$bildkat["kat_name"];

                                if($bildkat['bildirim_katid'] == 2){ 
                                    echo " ",$bilda["cevap"]; 
                                }
                                else if($bildkat['bildirim_katid'] == 3)
                                {
                                    echo " ",$bildunic["yorum"];
                                }
                                else if($bildkat['bildirim_katid'] == 4)
                                {
                                    echo " ",$bilda["cevap"]; 
                                }
                            }
                            else if($bildkat['bildirim_katid'] == 4)
                            {
                                echo " sorunuzdaki ",$bildkat["kat_name"];
                                echo " ",$bilda["cevap"]; 
                            }
                            else
                            {
                                echo "sorunuza cevap verdi: ",$bilda["cevap"];
                            }
                            ?>
                            </p>
                        </p>
                        <small style="font-size:12px; font-weight:200; position:absolute; bottom:7; right:15;" class="text-muted"> &nbsp;<? echo " ",zaman($bilda["bildirim_tarih"]); ?></small>
                    </div>
                </div>
        <? } 
        } ?>
    
</div>
</div>
<?php

footer();
?>