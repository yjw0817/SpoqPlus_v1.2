<style>
.overlay {
  position: fixed;
  top: 60px;
  left: 10px;
  right: 0;
  width: 95%;
  height: 0;
  background-color:#ffffff;
  z-index: 999;
}

.mem-info-header {
  position: sticky;
  top: 0;
  height: 400px;
  z-index: 999;
}


#bottom-menu-area {
    width:100%;
    border-radius: 10px 10px 0px 0px;
    border: solid 1px #bbbbbb;
    background-color:white;
}

.card-table
{
   padding: 0.2rem !important;
}

.tabmenu{
  width: 100%;
  overflow: auto;
}
.tabmenu ul{
  white-space:nowrap;
}
.tabmenu ul li{
  display: inline-block;
  padding: 0 10px;
}

#tabmenu{
    margin-top:7px;
    padding: 0px;
}

.tabmenu {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
.tabmenu::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera*/
}

.tab-pane
{
    font-size:0.9rem;
}

table#am-tb th{ background-color:#17a2b8; }
table#am-tb td{ background-color:white; }

table#am-tb thead { position: sticky; top: 0; z-index: 1; }
table#am-tb th:first-child,
table#am-tb td:first-child { position: sticky; left: 0; }

table#am-tb th:nth-child(2),
table#am-tb td:nth-child(2) { position: sticky; left: 62px; }


</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row">
			<div class="col-md-12">
                <div class="tabmenu" style='float:left'>
                    <ul id='tabmenu'>
                        <li><a href="javascript:;">상품1</a></li>
                        <li><a href="javascript:;">상품2</a></li>
                        <li><a href="javascript:;">상품3</a></li>
                        <li><a href="javascript:;">상품4</a></li>
                        <li><a href="javascript:;">상품5</a></li>
                        <li><a href="javascript:;">상품6</a></li>
                        <li><a href="javascript:;">상품7</a></li>
                        <li><a href="javascript:;">상품8</a></li>
                    </ul>
                </div>			
			</div>
		</div>
		
		
		
		<div class="row">
            <div class="col-md-4">
                <div class="card card-widget widget-user-2">
                    <div class="widget-user-header bg-warning">
                        <div class="widget-user-image">
                        	<img class="img-circle elevation-2" src="/dist/img/user8-128x128.jpg" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">Nadia Carmichael</h3>
                        <h5 class="widget-user-desc">Lead Developer</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            Projects <span class="float-right badge bg-primary">31</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            Tasks <span class="float-right badge bg-info">5</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            Completed Projects <span class="float-right badge bg-success">12</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            Followers <span class="float-right badge bg-danger">842</span>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<div class="row">
			<div class="col-md-12">
			
                <div class="panel-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <li class="item">
                            <div class="product-img">
                            	<img src="/dist/img/default-150x150.png" alt="Product Image" class="img-size-50" style="margin-left:5px;">
                            </div>
                            <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">헬스
                            	<span class="badge bg-warning float-right">300,000</span>
                            </a>
                            <span class="product-description">
                            	헬스 3개월 / 입장무제한
                            	<span class="badge bg-warning float-right">300,000</span>
                            </span>
                            </div>
                        </li>
                    
                        <li class="item">
                            <div class="product-img">
                            	<img src="/dist/img/default-150x150.png" alt="Product Image" class="img-size-50" style="margin-left:5px;">
                            </div>
                            <div class="product-info">
                            <a href="javascript:void(0)" class="product-title"><span class="badge bg-warning">입장무제한</span> PT
                            	<span class="badge bg-warning float-right">1,200,000</span>
                            </a>
                            <span class="product-description">
                            	헬스 3개월 
                            	<span class="badge bg-warning float-right">10 회</span>
                            </span>
                            </div>
                        </li>
                    
                        <li class="item">
                            <div class="product-img">
                            	<img src="/dist/img/default-150x150.png" alt="Product Image" class="img-size-50" style="margin-left:5px;">
                            </div>
                            <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">헬스
                            	<span class="badge bg-warning float-right">300,000</span>
                            </a>
                            <span class="product-description">
                            	헬스 3개월 / 입장무제한
                            	<span class="badge bg-warning float-right">300,000</span>
                            </span>
                            </div>
                        </li>
                    
                        <li class="item">
                            <div class="product-img">
                            	<img src="/dist/img/default-150x150.png" alt="Product Image" class="img-size-50" style="margin-left:5px;">
                            </div>
                            <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">헬스
                            	<span class="badge bg-warning float-right">300,000</span>
                            </a>
                            <span class="product-description">
                            	헬스 3개월 / 입장무제한
                            	<span class="badge bg-warning float-right">300,000</span>
                            </span>
                            </div>
                        </li>
                    
                    </ul>
                </div>			
			
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
			
			
				<div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-olive">
                        	<span class="info-box-icon"><i class="fas fa-running"></i></span>
                            <div class="info-box-content">
                            	<span class="info-box-text">헬스 3개월</span>
                            	<span class="info-box-number">57 일</span>
                                <div class="progress">
                                	<div class="progress-bar" style="width: 57%"></div>
                                </div>
                                <span class="progress-description">
                                100일 중 57일 이용중
                                </span>
                            </div>
                        </div>
                    </div>
        		</div>
        		
        		<div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-purple">
                        	<span class="info-box-icon"><i class="fas fa-child"></i></span>
                            <div class="info-box-content">
                            	<span class="info-box-text">PT 10회</span>
                            	<span class="info-box-number">7회</span>
                                <div class="progress">
                                	<div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <span class="progress-description">
                                10회 중 7회 이용중
                                </span>
                            </div>
                        </div>
                    </div>
        		</div>
        		
        		<div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-primary">
                        	<span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                            	<span class="info-box-text">그룹수업 3개월</span>
                            	<span class="info-box-number">20일</span>
                                <div class="progress">
                                	<div class="progress-bar" style="width: 22%"></div>
                                </div>
                                <span class="progress-description">
                                100일 중 20일 이용중
                                </span>
                            </div>
                        </div>
                    </div>
        		</div>
        		
        		<div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-secondary">
                        	<span class="info-box-icon"><i class="fas fa-key"></i></span>
                            <div class="info-box-content">
                            	<span class="info-box-text">락커 1개월</span>
                            	<span class="info-box-number">3일</span>
                                <div class="progress">
                                	<div class="progress-bar" style="width: 10%"></div>
                                </div>
                                <span class="progress-description">
                                30일 중 3일 사용중
                                </span>
                            </div>
                        </div>
                    </div>
        		</div>
			
			</div>
		</div>
		
		
		
		
		<div class="row">
			<div class="col-md-12">
			
			
				<div class="panel-body">

                <div id="accordion">
                <div class="card card-primary">
                <div class="page-header">
                <h4 class="panel-title w-100">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                Collapsible Group Item #1
                </a>
                </h4>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                <div class="panel-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                3
                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                laborum
                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                nulla
                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft
                beer
                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                labore sustainable VHS.
                </div>
                </div>
                </div>
                <div class="card card-danger">
                <div class="page-header">
                <h4 class="panel-title w-100">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                Collapsible Group Danger
                </a>
                </h4>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                <div class="panel-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                3
                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                laborum
                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                nulla
                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft
                beer
                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                labore sustainable VHS.
                </div>
                </div>
                </div>
                <div class="card card-success">
                <div class="page-header">
                <h4 class="panel-title w-100">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                Collapsible Group Success
                </a>
                </h4>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#accordion">
                <div class="panel-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                3
                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                laborum
                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                nulla
                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft
                beer
                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                labore sustainable VHS.
                </div>
                </div>
                </div>
                </div>
                </div>
                
			
			
			</div>
		</div>
		
		
		
		
		
		
		
		
		
		
		<div class="row">
			<div class="col-md-12">
			
    			<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        	<img class="d-block w-100" src="https://placehold.it/900x500/39CCCC/ffffff&text=I+Love+Bootstrap" alt="First slide">
                        </div>
                        <div class="carousel-item">
                        	<img class="d-block w-100" src="https://placehold.it/900x500/3c8dbc/ffffff&text=I+Love+Bootstrap" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                        	<img class="d-block w-100" src="https://placehold.it/900x500/f39c12/ffffff&text=I+Love+Bootstrap" alt="Third slide">
                        </div>
                    </div>
                </div>
			
			</div>
		</div>
		
		
		<div class="row" style='margin-top:7px;'>
            <div class="col-12 col-sm-6">
                <div class="card card-primary card-tabs">
                    <div class="page-header" style="padding:0px !important;">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                            	<a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">1번탭</a>
                            </li>
                            <li class="nav-item">
                            	<a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">2번탭</a>
                            </li>
                            <li class="nav-item">
                            	<a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">3번탭</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="panel-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            1번 텝 내용을 입력합니다. 이렇게 저렇게 입력합니다.
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            2번 텝 내용을 입력합니다. 이렇게 저렇게 입력합니다.
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                            3번 텝 내용을 입력합니다. 이렇게 저렇게 입력합니다.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" style='margin-top:7px;'>
            <div class="col-12 col-sm-6">
            
            	<div class="card">
                    <div class="page-header">
                        <h1 class="panel-title">상품현황</h1>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="panel-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active">
                            <a href="#" class="nav-link">
                            <i class="fas fa-inbox"></i> 추천상품
                            <span class="badge bg-primary float-right">7</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="far fa-envelope"></i> 예약상품
                            <span class="badge bg-info float-right">2</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="far fa-file-alt"></i> 이용상품
                            <span class="badge bg-success float-right">12</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="fas fa-filter"></i> 종료상품
                            <span class="badge bg-danger float-right">65</span>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            
            
            </div>
        </div>
        
	
		<div class="row">
			<div class="col-md-12">
			
				<table class="table table-bordered table-hover table-striped col-md-12">
					<thead>
						<tr class='text-center'>
							<th>타이틀1</th>
							<th>타이틀2</th>
							<th>타이틀3</th>
							<th>타이틀4</th>
						</tr>
					</thead>
					<tbody>
						<tr class='text-center'>
							<td>내용1</td>
							<td>내용2</td>
							<td>내용3</td>
							<td>내용4</td>
						</tr>
					</tbody>
                </table>
			
				<div class="card card-primary">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<h3 class="panel-title">Title</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<div class="panel-body card-table">
						<table class="table table-bordered table-hover table-striped col-md-12">
        					<thead>
        						<tr class='text-center'>
        							<th>타이틀1</th>
        							<th>타이틀2</th>
        							<th>타이틀3</th>
        							<th>타이틀4</th>
        						</tr>
        					</thead>
        					<tbody>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						
        					</tbody>
                        </table>
					</div>
					
					<div class="panel-body card-table" style='margin-top:7px;'>
						<a class="btn btn-app bg-secondary">
                        <span class="badge bg-success">300</span>
                        <i class="fas fa-barcode"></i> Products
                        </a>
                        <a class="btn btn-app bg-success">
                        <span class="badge bg-purple">891</span>
                        <i class="fas fa-users"></i> Users
                        </a>
                        <a class="btn btn-app bg-danger">
                        <span class="badge bg-teal">67</span>
                        <i class="fas fa-inbox"></i> Orders
                        </a>
                        <a class="btn btn-app bg-warning">
                        <span class="badge bg-info">12</span>
                        <i class="fas fa-envelope"></i> Inbox
                        </a>
					</div>
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> 이용중</strong>
                        <p class="text-muted">헬스 6개월
                            <span style='float:right'>
                            	<button type="button" class="btn btn-xs bottom-menu"><i class="fas fa-chevron-right"></i></button>
                            </span>
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> 이용중</strong>
                        <p class="text-muted">PT 10회
                            <span style='float:right'>
                            	<button type="button" class="btn btn-xs bottom-menu"><i class="fas fa-chevron-right"></i></button>
                            </span>
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> 이용중</strong>
                        <p class="text-muted">헬스 6개월</p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> 이용중</strong>
                        <p class="text-muted">PT 10회</p>
                        <hr>
                        
                        
					
					</div>
					
					
					<div class="panel-body table-responsive p-0" style="height: 300px;">
						<table class="table table-bordered text-nowrap" id="am-tb">
        					<thead>
        						<tr class='text-center'>
        							<th>타이틀1</th>
        							<th>타이틀2</th>
        							<th>타이틀3</th>
        							<th>타이틀4</th>
        							<th>타이틀1</th>
        							<th>타이틀2</th>
        							<th>타이틀3</th>
        							<th>타이틀4</th>
        							<th>타이틀1</th>
        							<th>타이틀2</th>
        							<th>타이틀3</th>
        							<th>타이틀4</th>
        						</tr>
        					</thead>
        					<tbody>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						<tr class='text-center'>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        							<td>내용1</td>
        							<td>내용2</td>
        							<td>내용3</td>
        							<td>내용4</td>
        						</tr>
        						
        					</tbody>
                        </table>
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <ul class="pagination pagination-sm m-0 float-left">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul>
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default"  data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->


<div class="overlay" style='display:none'>
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div class='bottom-title text-center'>메뉴 타이틀</div>
                <div class='bottom-content' style='margin-top:15px;'>
                
                    <table class="table table-bordered table-hover table-striped col-md-12">
    					<thead>
    						<tr class='text-center'>
    							<th>타이틀1</th>
    							<th>타이틀2</th>
    							<th>타이틀3</th>
    							<th>타이틀4</th>
    						</tr>
    					</thead>
    					<tbody>
    						<tr class='text-center'>
    							<td>내용1</td>
    							<td>내용2</td>
    							<td>내용3</td>
    							<td>내용4</td>
    						</tr>
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
$(function () {
    $('.select2').select2();
})

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
// 	var h_re = $(window).scrollTop() + 100;
// 	$(".overlay").animate({top: h_re+'px'});
});

$("#bottom-menu-close").click(function(){
	$(".overlay").hide();
// 	var h_size = $(window).height() + $(window).scrollTop();
// 	$( ".overlay" ).animate({
//     top : h_size+'px'
//       }, {
//         duration: 300,
//         complete: function() {
//           $(".overlay").hide();
//         }
//       });
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal("show");
});

// ===================== Modal Script [ END ] =============================

//Date picker
$('.datepp').datepicker({
    format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
    clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
    immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
    multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
    templates : {
        leftArrow: '&laquo;',
        rightArrow: '&raquo;'
    }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
    showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
    title: "날짜선택",	//캘린더 상단에 보여주는 타이틀
    todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false 
    toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
    weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일 
    
    //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
    //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
    //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
    //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
    //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
    //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
    //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
    
    language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

</script>