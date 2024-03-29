<?php
include "core.php";
head();
?>
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header"><i class="fas fa-search"></i> Search</div>
                    <div class="card-body">

<?php
if (isset($_GET['q'])) {
    $word = $_GET['q'];
    
    if (strlen($word) < 2) {
        echo '<div class="alert alert-warning">Aramak için en az 2 karakter girin.</div>';
    } else {
        
        $sql    = "SELECT * FROM posts WHERE active='Yes' AND (title LIKE '%$word%' OR content LIKE '%$word%') ORDER BY id DESC";
        $result = mysqli_query($connect, $sql);
        $row    = mysqli_fetch_assoc($result);
        $count  = mysqli_num_rows($result);
        if ($count == 0) {
            echo '<div class="alert alert-info">Sonuç bulunamadı.</div>';
        } else {
        
            echo '<div class="alert alert-success">' . $count . ' sonuç bulundu.</div>';

$postsperpage = 6;

$pageNum = 1;
if (isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
if (!is_numeric($pageNum)) {
    echo '<meta http-equiv="refresh" content="0; url=blog.php">';
    exit();
}
$rows = ($pageNum - 1) * $postsperpage;

$run   = mysqli_query($connect, "SELECT * FROM `posts` WHERE (title LIKE '%$word%' OR content LIKE '%$word%') AND active='Yes' ORDER BY id DESC LIMIT $rows, $postsperpage");
$count = mysqli_num_rows($run);
if ($count <= 0) {
    echo '<div class="alert alert-info">Yayınlanmış yorum yok.</div>';
} else {
    while ($row = mysqli_fetch_assoc($run)) {
        
        $image = "";
        if($row['image'] != "") {
            $image = '<img src="' . $row['image'] . '" alt="' . $row['title'] . '" style="width: 100%; height: 225px;">';
        } else {
            $image = '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
            <title>No Image</title><rect width="100%" height="100%" fill="#55595c"/>
            <text x="46%" y="50%" fill="#eceeef" dy=".3em">No Image</text></svg>';
        }
        
        echo '
                        <div class="card shadow-sm">
                            <a href="post.php?id=' . $row['id'] . '">
                                '. $image .'
                            </a>
                            <div class="card-body">
                                <a href="post.php?id=' . $row['id'] . '"><h5 class="card-title">' . $row['title'] . '</h5></a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="category.php?id=' . $row['category_id'] . '"><span class="badge bg-primary">' . post_category($row['category_id']) . '</span></a>
                                    <small><i class="fas fa-comments"></i> Comments: 
                                        <a href="post.php?id=' . $row['id'] . '#comments" class="blog-comments"><b>' . post_commentscount($row['id']) . '</b></a>
                                    </small>
                                </div>
                                <p class="card-text">' . short_text(strip_tags(html_entity_decode($row['content'])), 400) . '</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <b><i class="fas fa-user-edit"></i> ' . post_author($row['author_id']) . '</b>
                                    <small class="text-muted"><i class="far fa-calendar-alt"></i> ' . $row['date'] . ', ' . $row['time'] . '</small>
                                </div>
                            </div>
                        </div><br />
';
    }
    
    $query   = "SELECT COUNT(id) AS numrows FROM posts WHERE (title LIKE '%$word%' OR content LIKE '%$word%') AND active='Yes'";
    $result  = mysqli_query($connect, $query);
    $row     = mysqli_fetch_array($result);
    $numrows = $row['numrows'];
    $maxPage = ceil($numrows / $postsperpage);
    
    $pagenums = '';
    
    echo '<center>';
    
    for ($page = 1; $page <= $maxPage; $page++) {
        if ($page == $pageNum) {
            $pagenums .= "<a href='?q=$word&page=$page' class='btn btn-primary'>$page</a> ";
        } else {
            $pagenums .= "<a href=\"?q=$word&page=$page\" class='btn btn-default'>$page</a> ";
        }
    }
    
    if ($pageNum > 1) {
        $page     = $pageNum - 1;
        $previous = "<a href=\"?q=$word&page=$page\" class='btn btn-default'><i class='fa fa-arrow-left'></i> Önceki</a> ";
        
        $first = "<a href=\"?q=$word&page=1\" class='btn btn-default'><i class='fa fa-arrow-left'\></i> <i class='fa fa-arrow-left'></i> İlk</a> ";
    } else {
        $previous = ' ';
        $first    = ' ';
    }
    
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = "<a href=\"?q=$word&page=$page\" class='btn btn-default'><i class='fa fa-arrow-right'></i> Sonraki</a> ";
        
        $last = "<a href=\"?q=$word&page=$maxPage\" class='btn btn-default'><i class='fa fa-arrow-right'></i>  <i class='fa fa-arrow-r'></i> En son</a> ";
    } else {
        $next = ' ';
        $last = ' ';
    }
    
    echo $first . $previous . $pagenums . $next . $last;
    
    echo '</center>';
}
}
}
} else {
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit();
}
?>

                    </div>
                </div>
                
            </div>
<?php
sidebar();
footer();
?>