<style type="text/css" class="main">
	<?php $gapis_fsu = "//fonts.googleapis.com/css2?family="; ?>
	@import url('<?php echo$gapis_fsu;?>Akaya+Telivigala&display=swap');
	@import url('<?php echo$gapis_fsu;?>Balsamiq+Sans&display=swap');
	@import url('<?php echo$gapis_fsu;?>Bitter&display=swap');
	@import url('<?php echo$gapis_fsu;?>Caladea:wght@700&display=swap');
    @import url('<?php echo$gapis_fsu;?>Cormorant+Upright:wght@700&display=swap');
	@import url('<?php echo$gapis_fsu;?>Dancing+Script:wght@400;600&display=swap');
    @import url('<?php echo$gapis_fsu;?>Dosis:wght@500&display=swap');
	@import url('<?php echo$gapis_fsu;?>Itim&display=swap');
    @import url('<?php echo$gapis_fsu;?>Kanit:wght@200&display=swap');
	@import url('<?php echo$gapis_fsu;?>Modak&display=swap');
	@import url('<?php echo$gapis_fsu;?>Open+Sans&display=swap');
	@import url('<?php echo$gapis_fsu;?>Oswald:wght@700&display=swap');
	@import url('<?php echo$gapis_fsu;?>Permanent+Marker&display=swap');
	@import url('<?php echo$gapis_fsu;?>Prompt&display=swap');
	@import url('<?php echo$gapis_fsu;?>Quicksand:wght@600&display=swap');
    @import url('<?php echo$gapis_fsu;?>Ranchers&display=swap');
	@import url('<?php echo$gapis_fsu;?>Roboto:wght@300&display=swap');
    @import url('<?php echo$gapis_fsu;?>Sarabun:wght@300&display=swap');
	@import url('/resource/css/core/appfont.css');
	@import url('/resource/css/core/tclfont.css');
	@import url('//fonts.googleapis.com/icon?family=Material+Icons');
</style>
<script type="text/javascript">
    // Resizing
    $(function(){
		<?php if($require_sso)echo"app.sys.auth.sso('".($_GET['return_url']??"")."');"; ?>
		var main_height = $("html body main").height();
		$("html body header section div.head-item.menu a").on("click", function(){setTimeout(function(){$(window).trigger("resize");},500);});
		var $window = $(window).on('resize', function(){
			$("html body").css("--window-height", $(window).height().toString()+"px");
			var tlbw = [1.75, 0]; document.querySelectorAll("html body header section:nth-child(1) div.ocs div.head-item:not([hidden])").forEach((o) => { tlbw[0] += $(o).width(); }); $("html body header section:nth-child(1) div.ocs").css("min-width", tlbw[0].toString()+"px");
			// document.querySelectorAll("html body header section:nth-last-child(1) div.ocs div.head-item:not([hidden])").forEach((o) => { tlbw[1] += $(o).width(); }); $("html body header section:nth-last-child(1) div.ocs").css("min-width", tlbw[1].toString()+"px");
			document.querySelectorAll("html body header section:nth-last-child(1) div.ocs div.head-item:not([hidden])").forEach((o) => { tlbw[1] += $(o).width(); }); $("html body header section:nth-child(1)").css("max-width", ($("html body header").width()-tlbw[1]).toString()+"px");
		}).trigger('resize');
		ppa.check_lang(); ppa.check_theme(); ppa.color_up_codes();
		if (self != top) {
			$("html body header").remove();
			$("html body aside.navigator_tab").remove();
			$("html body footer").remove();
			$("html body").addClass("nohbar");
		} else ppa.console_proof();
		document.querySelectorAll("html body header section div.ocs div.head-item:not(.logo) a").forEach((menu) => { if ($(menu).attr("href").split("?")[0].split("#")[0]==location.pathname) menu.classList.add("ftcpm"); }); $("a:not([draggable]), img:not([draggable])").attr("draggable", "false");
    });
	// Scrolling
	$(document).scroll(function() {
		// setHash($(document).scrollTop());
		$("html body aside.up").css("display", (($(document).scrollTop() > $(window).height() - 50)?"block":"none"));
		if ($(document).scrollTop()>0) $("html body header:not(.scrolled)").addClass("scrolled");
		else $("html body header.scrolled").removeClass("scrolled");
	});
	function smooth_scrolling(event) {
		if (this.hash !== "") {
			event.preventDefault();
			var hash = this.hash;
			$('html, body').animate({
				scrollTop: $(hash).offset().top
			}, 800, function(){
				window.location.hash = hash;
			});
		}
	}
	$("a").on('click', function(event) { smooth_scrolling(event); });
</script>