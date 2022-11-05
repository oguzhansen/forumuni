<?php
include "header.php";
?>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h3 class="h3"><i class="far fa-envelope"></i> Newsletter Subscribers</h3>
	</div>

            <div class="card">
              <h6 class="card-header">Subscribers</h6>         
                  <div class="card-body">
                    <table class="table table-border table-hover" id="dt-basic">
                          <thead>
                              <tr>
                                  <th>E-Mail</th>
                              </tr>
                          </thead>
                          <tbody>
<?php
$query = mysqli_query($connect, "SELECT * FROM newsletter ORDER by email ASC");
while ($row = mysqli_fetch_assoc($query)) {
    echo '
                            <tr>
                                <td>' . $row['email'] . '</td>
                            </tr>
';
    }
?>
                          </tbody>
                     </table>
                  </div>
            </div>

<script>
$(document).ready(function() {

	$('#dt-basic').dataTable( {
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"language": {
			"paginate": {
			  "previous": '<i class="fa fa-angle-left"></i>',
			  "next": '<i class="fa fa-angle-right"></i>'
			}
		}
	} );
} );
</script>
<?php
include "footer.php";
?>