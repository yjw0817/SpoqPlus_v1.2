<!-- Navbar Search -->
<?php
if (isset($_SESSION['site_type'])) :
if ($_SESSION['site_type'] == "tlogin") :
?>
		<li class="nav-item only_desktop">
			<div class="navbar-search-block">
				<!-- <form class="form-inline"> -->
					<div class="input-group input-group-sm">
                    	<input type="text" class="form-control" style='width:220px' placeholder="회원명 또는 전화번호 뒷자리" name="top_search" id="top_search">
                    	<span class="input-group-append">
                        	<button type="button" class="btn btn-info btn-flat" id="btn_top_search" onclick="ff_tsearch();">검색</button>
                        </span>
					</div>
				<!-- </form> -->
			</div>
		</li>
<?php
endif;
endif;
?>		