<div class="col-xs-3" style="margin-top:5%;">

					  <?php
					  $id = -1;
					  $leftlist = "";
					  if(!isset($_GET['id'])){
						
						$leftlist = '<li role="presentation" class="active" ><a href="parent-home.php">Home</a></li>';
					  }
					  else{
						$id = $_GET['id'];
						$leftlist = '<li role="presentation" style="border:1px solid #C2E6FF;border:1px solid #B2B2B2;border-radius:4px;"><a href="parent-home.php">Home</a></li>';
						}
					  
					  for($k=0;$k<$z;$k++)
					  {
					  global $leftlist;
						if($id == $confirmed_children_ids[$k]){
							$leftlist = $leftlist.'<li role="presentation" class="active" style="border:1px solid #C2E6FF;border:1px solid #B2B2B2;border-radius:4px;"><a  href="pie.php?id='.$confirmed_children_ids[$k].'">'.$confirmed_children_names[$k].'</a></li>';
							}
							else{
								$leftlist = $leftlist.'<li role="presentation" style="border:1px solid #C2E6FF;border:1px solid #B2B2B2;border-radius:4px;"><a  href="pie.php?id='.$confirmed_children_ids[$k].'">'.$confirmed_children_names[$k].'</a></li>';
							}
					  }
					  
					  ?>
					<ul class="nav nav-pills nav-stacked">
					<?php echo $leftlist;?>
					</ul>
</div>