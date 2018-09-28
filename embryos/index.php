<?php
include_once("conn.php");
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <title>Eggan/Melton Embryos Database</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <link href="styles.css" rel="stylesheet" type="text/css" />
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous">
  </script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
  <script language="javascript">
    (function( $ ) {
      $(document).ready(function(){
        $(".datatable").DataTable({
          "paging": false,
          "order": []
        });
      });
    })( jQuery );

  </script>
  </head>
  <body>
    <div id="wrapper">
        <h2 class="cntr"><a href="index.php">Eggan/Melton Embryos Database</a></h2>
        <?php
          function date_us($tmstmp) {
            if ($tmstmp == 0) {
              $retval = "&nbsp;";
            }
            else {
              $retval = date("m/d/y", $tmstmp);
            }

            return $retval;
          }

		      function date_su($dt) {
		  	    $dtVals = explode("/", $dt);
			      if (checkdate($dtVals[0], $dtVals[1], $dtVals[2])) {
		  		    $retval = mktime(0, 0, 0, $dtVals[0], $dtVals[1], $dtVals[2]);
			      }
			      else {
				      $retval = 0;
			      }

			      return $retval;
		      }

		      function null_check($value) {
		  	    $retval = $value;
		  	    if ($value == "" || $value==0) { $retval = "&nbsp;"; }
			      return $retval;
		      }

		      function img_view($imgName, $img, $embryo) {
		  	    if (is_null($imgName)) {
				      $retval = '&nbsp;';
			      }
			      else {
				      $retval = '<a href="img_view.php?id='.$embryo.'&which='.$img.'">View</a>';
			      }
			      return $retval;
		      }

          // default search field
          $s_fld = "name";
          $slctd = ' selected="selected"';


			    $t = array("uses" => "use_desc", "outcomes" => "outcome", "users" => "username");

			    foreach ($t as $k => $v) {
				    $tmpArray = array();
				    $query = "select id, disp, $v from $k ORDER BY $v";
				    $result = mysql_query($query) or die('Error, query disp failed ' . $query);
				    while($r = mysql_fetch_assoc($result)) {
					    $tmpArray[$r['id']] = $r['disp'].$r[$v];
				    }
				    $t[$k] = $tmpArray;
				    mysql_free_result($result);
			    }
		  ?>

        <div class="section search">
        <?php
            // Searching is called for
            $query =  "SELECT group_concat(u.use_desc SEPARATOR ', ') as uses, e.id, e.name, e.nickname, e.date_frozen, e.date_thawed, e.fresh, e.date_outcome, e.uses_other, e.hues_num, e.notes, e.img_r_name, e.img_f_name, e.outcome, e.approved_1, e.approved_2 " .
                      "FROM embryos e left join embryos_uses eu on e.id = eu.embryo_id inner join uses u on u.id=eu.use_id " .
                      "GROUP BY e.id, e.name, e.nickname, e.date_frozen, e.date_thawed, e.fresh, e.date_outcome, e.uses_other, e.hues_num, e.notes, e.img_r_name, e.img_f_name, e.outcome, e.approved_1, e.approved_2";
            $result = mysql_query($query) or die('Error, embryo query failed');

            if (mysql_num_rows($result) == 0) {
                echo "<p><em>No matching records were found.</em></p>";
            }
            else {
             ?>
                  <p>
                    <table class="datatable display" cellpadding="3" cellspacing="1">
                      <thead>
                        <tr>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>HUES #</th>
                            <th>Date Frozen</th>
                            <th>Date Thawed</th>
                            <th>Uses</th>
                            <th>Uses Other</th>
                            <th>Outcome</th>
                            <th>Date Outcome</th>
                            <th>Img - Received</th>
                            <th>Img - Final</th>
                            <th>Approved 1</th>
                            <th>Approved 2</th>
                            <th>Notes</th>
                        </tr>
                      </thead>
                      <tbody>
              <?php
                while($row = mysql_fetch_assoc($result)) {
                  $strUses = "";
                  if (array_key_exists('uses', $row)){
                    $strUses = $row['uses'];
                  }
                  // $q_uses = "SELECT use_id from embryos_uses where embryo_id = ".$row['id'];
                  // $res_uses = mysql_query($q_uses) or die('Error, query failed');
                  // if (mysql_num_rows($res_uses) > 0) {
                  //   while($row_uses = mysql_fetch_assoc($res_uses)) {
                  //     $strUses .= substr($t['uses'][$row_uses['use_id']], 1).', ';
                  //   }
                  //   $strUses = substr($strUses, 0, strlen($strUses)-2);
                  // }
                  // mysql_free_result($res_uses);

                  $strFrozen = date_us($row['date_frozen']);
                  if ($row['fresh']) {$strFrozen = "Fresh";}

                  $rowdata = array();
                  $cols = array('name', 'nickname', 'hues_num', 'date_thawed', 'uses_other', 'outcome', 'date_outcome', 'img_r_name', 'img_f_name','id', 'approved_1', 'approved_2', 'notes');
                  foreach ($cols as $col){
                    if (array_key_exists($col, $row)){
                      $rowdata[$col] = $row[$col];
                    }
                    else {
                      $rowdata[$col] = '';
                    }
                  }

                  echo '<tr class="results_row">'.
                          '<td><strong>'.$rowdata['name'].'</strong></td>'.
                          '<td>'.$rowdata['nickname'].'</td>'.
                          '<td>'.null_check($rowdata['hues_num']).'</td>'.
                          '<td>'.$strFrozen.'</td>'.
                          '<td>'.date_us($rowdata['date_thawed']).'</td>'.
                          '<td>'.$strUses.'</td>'.
                          '<td>'.$rowdata['uses_other'].'</td>'.
                          '<td>'.substr($t['outcomes'][$rowdata['outcome']], 1).'</td>'.
                          '<td>'.date_us($rowdata['date_outcome']).'</td>'.
                          '<td>'.img_view($rowdata['img_r_name'], 'r', $rowdata['id']).'</td>'.
                          '<td>'.img_view($rowdata['img_f_name'], 'f', $rowdata['id']).'</td>'.
                          '<td>'.substr($t['users'][$rowdata['approved_1']], 1).'</td>'.
                          '<td>'.substr($t['users'][$rowdata['approved_2']], 1).'</td>'.
                          '<td>'.$rowdata['notes'].'</td>'.
                        '</tr>';
                }
              ?>
                    </tbody>
                  </table>
                  </p><br />
                <?php
                  echo "<p><em>Search Results: ".mysql_num_rows($result)."</em></p>";
              }
              mysql_free_result($result);


           // End if search
          // Closing connection
          if (isset($connection)) {mysql_close($connection);}
        ?>
      </div>
    </div>
  </body>
</html>
