<style>
.fc .fc-timegrid-slot-label-cushion
{
    font-size: 0.8rem;
}

.fc-v-event .fc-event-title
{
    font-size: 0.8rem;
}
</style>

<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 only_desktop">
					<div class="sticky-top mb-3">
						<div class="card">
							<div class="page-header">
								<h4 class="panel-title">반복 할일</h4>
							</div>
							<div class="panel-body">
								<!-- the events -->
								<div id="external-events">
								<div class="external-event bg-success" data-tid="--id--">Lunch</div>
								<div class="external-event bg-warning">Go home</div>
								<div class="external-event bg-info">Do homework</div>
								<div class="external-event bg-primary">Work on UI design</div>
								<div class="external-event bg-danger">Sleep tight</div>
								<div class="checkbox">
									<label for="drop-remove">
										<input type="checkbox" id="drop-remove">
											remove after drop
									</label>
								</div>
							</div>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.card -->
					<div class="card">
						<div class="page-header">
							<h3 class="panel-title">할일 만들기</h3>
						</div>
						<div class="panel-body">
							<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
								<ul class="fc-color-picker" id="color-chooser">
									<li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
									<li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
									<li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
									<li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
									<li><a class="text-pink" href="#"><i class="fas fa-square"></i></a></li>
								</ul>
							</div>
							<!-- /btn-group -->
							<div class="input-group">
								<input id="new-event" type="text" class="form-control" placeholder="Event Title">

								<div class="input-group-append">
									<button id="add-new-event" type="button" class="btn btn-primary">Add</button>
								</div>
								<!-- /btn-group -->
							</div>
							<!-- /input-group -->
						</div>
					</div>
				</div>
			</div>
          	<!-- /.col -->
			<div class="col-md-9">
				<div class="card card-primary">
					<div class="panel-body p-0">
						<!-- THE CALENDAR -->
						<div id="calendar" class="calendar"></div>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
    
<?=$jsinc ?>	

<script>


</script>
    
    
    