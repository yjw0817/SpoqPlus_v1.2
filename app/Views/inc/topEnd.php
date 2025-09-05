		<!-- 관리자 메뉴 아이콘 --		
		<li class="nav-item only_desktop">
			<a class="nav-link" href="#" >
				<i class="fas fa-cog"></i>
			</a>
		</li>
		-->
		<!-- 회원검색-->
		<?php if (isset($_SESSION['site_type'])) :?>
		<?php if($_SESSION['site_type'] == "mtlogin") :?>
		<li class="nav-item only_mobile">
			<a class="nav-link" href="/api/tmem_usearch" role="button">
				<i class="fas fa-search"></i>
			</a>
		</li>
		<?php endif; ?>
		<?php endif; ?>
		<li class="nav-item only_mobile">
			<a class="nav-link mobile_back" href="javascript:history.back();" role="button">
				<!-- <i class="fas fa-arrow-circle-left"></i> -->
				<i class="fas fa-arrow-left only_mobile" style='margin-top:4px;' onclick="javascript:history.back();"></i>
			</a>
		</li>
		<li class="nav-item only_mobile mobile_back_v" style='display:none;width:40px;'>
		</li>
		
		<!-- 전체화면 전환 버튼--
		<li class="nav-item only_desktop">
			<a class="nav-link" data-widget="fullscreen" href="#" role="button">
				<i class="fas fa-expand-arrows-alt"></i>
			</a>
		</li>
		-->
		<!-- 오른쪽 사이드바 버튼--
		<li class="nav-item only_desktop">
			<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
				<i class="fas fa-th-large"></i>
			</a>
		</li>
		-->

	</ul>
</nav>
<!-- /.navbar -->