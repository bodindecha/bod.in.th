<header>
    <section class="slider hscroll sscroll"><div class="ocs">
		<div class="head-item menu">
			<a onClick="app.ui.toggle.navtab()" href="javascript:void(0)" opened="<?php echo ($_COOKIE['sui_open-nt']??"false"); ?>"><div>
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>
			</div></a>
		</div>
		<?php
			if (isset($_SESSION['auth'])) echo '
				<div class="head-item logo contain-img text">
					<a href="/dashboard"><img src="/resource/images/logo.png" data-dark="false"><span>หน้าแรก</span><!--span>DPST⨯SMTE</span--></a>
				</div>
				<div class="head-item text">
					<a href="/dashboard"><span>แผงควบคุม</span></a>
					<a onClick="app.sys.auth.out()" href="javascript:void(0)"><span>ออกจากระบบ</span></a>
				</div>
			'; else echo '
				<div class="head-item logo contain-img text">
					<a href="/"><img src="/resource/images/logo.png" data-dark="false"><span>หน้าแรก</span><!--span>DPST⨯SMTE</span--></a>
				</div>
				<div class="head-item text">
					<a onClick="app.sys.auth.orize(\''.rtrim(ltrim($_SERVER['REQUEST_URI'], "/"), "/").'\')" href="javascript:void(0)"><span>เข้าสู่ระบบ</span></a>
				</div>
			';
		?>
	</div></section>
    <section class="slider hscroll sscroll"><div class="ocs">
		<div class="head-item lang"><select name="hl">
			<option>th</option>
			<option>en</option>
		</select></div>
		<div class="head-item clrt contain-img">
			<a onCLick="app.ui.change.theme('dark')" href="javascript:void(0)"><i class="material-icons">brightness_6</i></a>
		</div>
	</div></section>
</header>