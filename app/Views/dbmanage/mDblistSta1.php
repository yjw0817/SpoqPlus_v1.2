<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">디비통계(대행사별)</h3>
					</div>
					<div class="panel-body">
						<form name='stForm' id='stForm' method='post' action='/dbmanage/mDblistSta1'>
						
						<select name='syy' class='input-sm' style="height: calc(1.90rem + 2px) !important;">
						<?php for($y=date('Y');$y>2020;$y--) : ?>
							<?php if ($y == $syy): ?>
								<option value='<?=$y?>' selected><?=$y?></option>
							<?php else: ?>
								<option value='<?=$y?>'><?=$y?></option>
							<?php endif; ?>
						<?php endfor; ?>
						</select>
						
						<select name='smm' class='input-sm' style="height: calc(1.90rem + 2px) !important;">
							<?php for($m=1;$m<13;$m++) : ?>
							
								<?php if (sprintf('%02d', $m) == $smm): ?>
									<option value='<?=sprintf('%02d', $m)?>' selected><?=sprintf('%02d', $m)?></option>
								<?php else: ?>
									<option value='<?=sprintf('%02d', $m)?>'><?=sprintf('%02d', $m)?></option>
								<?php endif; ?>
							<?php endfor; ?>
						</select>
						
						<a class='btn btn-sm btn-success' onclick="stSearch();">검색</a>
						
						</form>
						
						<table class="table table-bordered table-hover table-striped col-md-12">
						
							<thead>
								<tr>
									<th>대행사명</th>
									<?php
									for($i=1; $i<$day_count+1; $i++):
									?>
									<th><?=$i?></th>
									<?php
									endfor;
									?>
									<th>합계</th>
								</tr>
							</thead>
							<tbody>
							<?php
							for($i2=1; $i2<$day_count+1; $i2++):
								$bottom_total[$i2] = 0;
							endfor;
							$all_total = 0;
							foreach ($dbgroup as $g):
							?>
								<tr>
									<td><?=$g['comp_name']?></td>
									<?php
									$temp_total = 0;
									
									for($i=1; $i<$day_count+1; $i++):
									$bottom_total[$i] += $dblist[$g['company_idx']][sprintf('%02d', $i)];
									?>
									<td>
									<?php if ($dblist[$g['company_idx']][sprintf('%02d', $i)] != 0) : ?>
										<?=$dblist[$g['company_idx']][sprintf('%02d', $i)]?>
									<?php
									$temp_total += $dblist[$g['company_idx']][sprintf('%02d', $i)];
									
										endif; 
									?>
									</td>
									<?php
									endfor;
									$all_total += $temp_total;
									?>
									<td><?=$temp_total?></td>
								</tr>
							<?php
							endforeach;
							
							echo '<tr><td>합계</td>';
							for($i2=1; $i2<$day_count+1; $i2++):
								echo '<td>' . $bottom_total[$i2] . '</td>';
							endfor;
							echo '<td>' . $all_total . '</td></tr>';
							?>
							</tbody>
						</table>

						
					</div>
				</div>

			</div>
		</div>

	</div>
	
</section>

<?=$jsinc ?>

<script>
	function stSearch()
	{
		var f = document.stForm;
		f.submit();
	}
</script>