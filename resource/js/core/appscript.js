// Private
const app_name = "Bodindecha (Sing Singhaseni) School's Information System",
	color_light = "#15499A", color_dark = "#8CC0FF";
function private() {
	var console_proof = function() {
		console.log(
			"%cStop!\n%cThis is a browser feature intended for developers. If someone told you to copy and paste something here to enable a "+app_name+" feature or \"hack\" this web application, it's a scam and will give them access to "+app_name+" system.",
			"color: red; font-family: system-ui; font-size: 5rem; -webkit-text-stroke: 1px black; font-weight: bold;",
			"font-family: system-ui; font-size: 1.75rem;"
		  );
	}
	var set_long_term_cookie = function(ckey, cval) {
		var expire = new Date(); expire.setTime(expire.getTime() + (365*24*60*60*1000));
		document.cookie = ckey+"="+cval.toString()+";expires="+expire+";path=/";	
	}
	var context_menu_program = function() {
		var context_meu = $("html body nav.cm"); var cm_ready = false;
		if (history.length<3) $("html body nav.cm ul.nav li.next").attr("disabled", "");
		if (history.length<2) $("html body nav.cm ul.nav li.back").attr("disabled", "");
		function exit_cm() {
			if (cm_ready) {
				context_meu.css("box-shadow", "none");
				context_meu.css("height", "0px");
				context_meu.css("border", "none");
				cm_ready = false;
			}
		}
		function on_cm(event) {
			exit_cm(); context_meu.css("height", "auto");
			var leftx = event.pageX; leftx = (leftx+context_meu.width()>=$(window).width()?$(window).width()-context_meu.width():leftx);
			var topy = event.pageY-$(document).scrollTop(); topy = topy-(topy+context_meu.height()>=$(window).height()?context_meu.height():0);
			context_meu.css("top", topy.toString()+"px"); context_meu.css("left", leftx.toString()+"px");
			context_meu.css("box-shadow", "0px 0px 3.45px 2.3px rgba(0, 0, 0, 0.23)");
			context_meu.css("border", "0.25px solid var(--fade-black-5)");
			cm_ready = true;
		}
		try {
			$("*").on("contextmenu",function(event) { on_cm(event); return false; });
			$(window).on("click scroll blur", exit_cm);
		} catch (err) {}
	}
	var getCookie = function(gc_cname) {
	  var gc_name = gc_cname + "=";
	  var gc_ca = decodeURIComponent(document.cookie).split(';');
	  for(var gc_i = 0; gc_i < gc_ca.length; gc_i++) {
		var gc_c = gc_ca[gc_i];
		while (gc_c.charAt(0) == ' ') {
		  gc_c = gc_c.substring(1);
		}
		if (gc_c.indexOf(gc_name) == 0) {
		  return gc_c.substring(gc_name.length, gc_c.length);
		}
	  }
	  return "";
	}
	var check_lang = function() {
		var current_lang = ppa.getCookie("set_lang");
		var path = window.location.pathname.substr(1);

		if (path == "") { path = "index"; }

		if (current_lang == null || current_lang == "" || current_lang == undefined) {
			app.ui.change.lang("th", false); if (self == top) location.reload();
		} else {
			if (current_lang == "th") {
				$("html body nav ul.nav li.back span").text("ย้อนกลับ");
				$("html body nav ul.nav li.reload span").text("โหลดใหม่");
				$("html body nav ul.nav li.next span").text("ถัดไป");
				$("html body nav ul.social li.share span").text("แชร์หน้านี้");
				$("html body nav ul.social li.copyurl span").text("คัดลอกลิ้งก์");
				$("html body nav ul.action li.print span").text("ปริ้นท์หน้านี้");
			} else if (current_lang == "en") {
				$("html body nav ul.nav li.back span").text("Back");
				$("html body nav ul.nav li.reload span").text("Reload page");
				$("html body nav ul.nav li.next span").text("Forward");
				$("html body nav ul.social li.share span").text("Share this page");
				$("html body nav ul.social li.copyurl span").text("Copy page URL");
				$("html body nav ul.action li.print span").text("Print page");
			}
			// $.ajax({url: "/resource/lang/"+current_lang+"_"+path+".js"});
			document.querySelectorAll('select[name="hl"] option').forEach((eo) => {
				if (eo.innerText==current_lang) eo.selected = true;
			});
		}
	}
	var lang_change_program = function() {
		$('select[name="hl"]').on("change", function() {
			let new_lang = document.querySelector('select[name="hl"] option:checked').innerText;
			app.ui.change.lang(new_lang);
		});
	}
	// const page_style = $("html head style.main").text();
	var check_theme = function(wait = true) {
		setTimeout(function() {
			var current_theme = ppa.getCookie("set_theme");
			if (current_theme == null || current_theme == "" || current_theme == undefined) {
				app.ui.change.theme("light", false); if (self == top) location.reload();
			} else {
				if (current_theme == "light" && !wait) {
					// $("html head style.main").text(page_style);
					$("html head style.main").text($("html head style.main").text().substring(0, $("html head style.main").text().length - 82));
					$("html body header section div.head-item.clrt a").attr("onClick", "app.ui.change.theme('dark')");
					$("html head meta[name=\"theme-color\"]").attr("content", color_light);
					$("html head meta[name=\"apple-mobile-web-app-status-bar-style\"]").attr("content", color_light);
				} else if (current_theme == "dark") {
					// $("html head style.main").text(page_style+"\n:root,[data-dark=\"false\"],.player{filter:invert(100%)hue-rotate(180deg);}\n");
					$("html head style.main").text($("html head style.main").text()+"\n:root,[data-dark=\"false\"],.player,iframe{filter:invert(100%)hue-rotate(180deg);}\n");
					$("html body header section div.head-item.clrt a").attr("onClick", "app.ui.change.theme('light')");
					$("html head meta[name=\"theme-color\"]").attr("content", color_dark);
					$("html head meta[name=\"apple-mobile-web-app-status-bar-style\"]").attr("content", color_dark);
				}
			}
		}, wait?575:0);
	}
	// Keyboard shortcuts (Enable | Disable)
	var prevkey = [];
	$(document).on("keydown", function(e){
		let prik = e.which || e.keyCode, ckeyp = String.fromCharCode(prik) || e.key || e.code, isCrtling = e.ctrlKey, isShifting = e.shiftKey, isAlting = e.altKey;
		prevkey.push(prik); if (prevkey.length > 3) prevkey.shift();
		if (prik == 123 || (isCrtling && isShifting && prik == 73)) { e.preventDefault(); app.ui.notify(1, [2, "Function reserved for developers"]); } // F12 || Ctrl + Shift + I
		else if (isCrtling && prik == 83) { e.preventDefault(); app.ui.notify(1, [2, "You can't save this webpage"]); } // Ctrl + S
		// else if (prevkey[0] == 91 && isShifting && prik == 83) { app.ui.notify(1, [1, "Snipping tool detected"]); } // Home + Shift + S
		else if (isAlting && prik == 84) { app.ui.notify(1, [0, "Theme toggled"]); document.querySelector("html body header section div.head-item.clrt a").click(); } // Alt + t
		else if (isAlting && prik == 76) { app.ui.notify(1, [0, "Language changed"]); app.ui.toggle.langes(); } // Alt + l
		else if (isAlting && prik == 72) { app.io.warpto(""); } // Alt + h
	});
	$(document).on("keyup", function(e){
		let prik = e.which || e.keyCode, ckeyp = String.fromCharCode(prik) || e.key || e.code, isCrtling = e.ctrlKey, isShifting = e.shiftKey, isAlting = e.altKey;
		prevkey.push(prik); if (prevkey.length > 3) prevkey.shift();
		if (prik == 44) { stopPrntScr(); app.ui.notify(1, [3, "Print Screen detected & blocked"]); } // Print screen
	});
	var stopPrntScr = function() {
		var inpFld = document.createElement("input");
		inpFld.setAttribute("value", ".");
		inpFld.setAttribute("width", "0");
		inpFld.style.height = "0px";
		inpFld.style.width = "0px";
		inpFld.style.border = "0px";
		document.body.appendChild(inpFld);
		inpFld.select();
		document.execCommand("copy");
		inpFld.remove(inpFld);
		try { window.clipboardData.setData('text', "Access Restricted"); } catch (err) {}
	}
	var write_loc_href = function() {
		$("html body nav.rfr").text(location.hostname+(location.pathname=="/"?"":location.pathname));
	}
	var fm_clrsrc = ["done", "info", "warn", "error"], ctrling = false, lghtbx = {at:null, ready:true};
	$(document).on("keydown keyup", function(e){ ctrling = e.ctrlKey; });
	var color_up_codes = function() {
		(document.querySelectorAll("code")).forEach((ce) => {
			var ct = ce.innerHTML, cl = $(ce).attr("lang");
			if (cl == "html") {
				ct = ct.replace(new RegExp("&", "g"), "&amp;").replace(new RegExp("<", "g"), "&lt;"); // .replace(new RegExp(">", "g"), "&rt;");
				Prism.highlightElement(ce, Prism.languages.html, "html");
			} else if (cl == "css") {
				Prism.highlightElement(ce, Prism.languages.css, "css");
			} else if (cl == "js") {
				try { (ct.match(/^\/\/.*\n$/g)).forEach((w) => { ct = ct.replace(new RegExp(w, "g"), w.fontcolor("var(--clr-pp-amber-300)")); }); } catch(e){}
				try { (ct.match(/^("|").*("|")$/g)).forEach((w) => { ct = ct.replace(new RegExp(w, "g"), w.fontcolor("var(--clr-pp-amber-300)")); }); } catch(e){}
				ce.innerHTML = ce.innerHTML.replace(/<br>/g, " enil0wen0 ");
				Prism.highlightElement(ce, Prism.languages.javascript, "javascript");
			}
		});
	}
	var clean_up_codes = function() {
		var clean = setInterval(function() { (document.querySelectorAll("code")).forEach((ce) => {
			var cl = $(ce).attr("lang"), clearance = false;
			if (cl == "js") {
				ce.innerHTML = ce.innerHTML.replace(/ enil0wen0 /g, "<br>");
				ce.innerHTML = ce.innerHTML.replace(/ \*\//g, "");
				ce.innerHTML = ce.innerHTML.replace(/\/\*/g, "//");
				clearance = /( enil0wen0 | \*\/|\/\*)/g.test(ce.innerHTML)
			} else clearance = true;
			if (clearance) clearInterval(clean);
		}); }, 500); setTimeout(function() { clearInterval(clean); }, 5000);
	}
	var target, targeting = false; var ripple_click_program = function() {
		const fxgs = '<span class="ripple-effect"></span>';
		document.querySelectorAll(".ripple-click").forEach((eae) => {
			let eao = $(eae); let size = Math.sqrt(Math.pow(eao.width(), 2) + Math.pow(eao.height(), 2))*2;
			eao.css("--rpc-s", size.toString()+"px");
			if (!eao.html().startsWith(fxgs)) eao.prepend(fxgs);
		}); var action = $(".ripple-click");
		function ripple_start(e) {
			target = $(e.target); targeting = true; let eX, eY;
			try { eX = e.pageX; eY = e.pageY; } catch(ex){}
			try { eX = e.touches[0].clientX; eY = e.touches[0].clientY; } catch(ex){}
			action.css("--rpc-x", (eX-target.offset().left).toString()+"px");
			action.css("--rpc-y", (eY-target.offset().top).toString()+"px");
		}
		function ripple_stop() {
			if (targeting) {
				target.addClass("ripple-done"); setTimeout(function() {
					target.removeClass("ripple-done");
				}, 500);
				targeting = false;
			}
		}
		action.on("mousedown touchstart", function(event) { ripple_start(event); });
		$("*").on("mouseup touchend", function(event) { ripple_stop(event); });
	}
	var img_zoom_out = function() {
		var selector = 'main :not(a) img[src^="/"][src$=".png"], main :not(a) img[src^="/"][src$=".jpg"], main :not(a) img[src^="/"][src$=".jpeg"], main :not(a) img[src^="/"][src$=".gif"]';
		function oinw(event, require_ctrl) {
			if (!require_ctrl || (require_ctrl && ctrling)) {
				var localurl = $(event.target).attr("src");
				// var viewurl = encodeURI(localurl.substr(1, localurl.length));
				open(localurl, "_blank", "width=420,height=420,noreferrer,top=180"); // src="/resource/images/logo.png"
			}
		}
		$(selector).on("dblclick", function(e) { oinw(e, false); });
		$(selector).on("click", function(e) { oinw(e, true); });
	}
	return {
		console_proof: console_proof,
		set_long_term_cookie: set_long_term_cookie,
		context_menu_program: context_menu_program,
		lang_change_program: lang_change_program,
		image_open_program: img_zoom_out,
		getCookie: getCookie,
		check_lang: check_lang,
		check_theme: check_theme,
		stopPrntScr: stopPrntScr,
		fm_clrsrc: fm_clrsrc,
		write_loc_href: write_loc_href,
		color_up_codes: color_up_codes,
		clean_up_codes: clean_up_codes,
		ripple_click_program: ripple_click_program,
		ctrling: ctrling
	};
} var ppa = private();
$(document).ready(function() {
	ppa.context_menu_program();
	ppa.ripple_click_program();
	ppa.lang_change_program();
	ppa.image_open_program();
	ppa.write_loc_href();
	Prism.hooks.add("complete", ppa.clean_up_codes);
});
// Public
function initial_app() {
	var toggle_uibtn = function(object) {
		var menuobj = $("html body header section div.head-item.menu a"),
			mainobj = $("html body main"),
			ntpanel = $("html body aside.navigator_tab");
		if (object == "navtab") {
			ppa.set_long_term_cookie("sui_open-nt", menuobj.attr("opened")=="false");
			ntpanel.attr("opened", (menuobj.attr("opened")=="false"?"true":"false"));
			menuobj.attr("opened", (menuobj.attr("opened")=="false"?"true":"false"));
			mainobj.attr("shrink", (mainobj.attr("shrink")=="false"?"true":"false"));
		}
	}
	var copy_page_url = function() {
		const elem = document.createElement('textarea');
		elem.value = location.href;
		document.body.appendChild(elem);
		elem.select();
		document.execCommand('copy');
		document.body.removeChild(elem);
		app.ui.notify(1, [0, "Page URL copied!"]);
	}
	var switch_lang = function(chosen, next = true) {
		var expire = new Date(); expire.setTime(expire.getTime() + (365*24*60*60*1000));
		document.cookie = "set_lang="+chosen+";expires="+expire+";path=/";
		if (next) ppa.check_lang();
	}
	var toggle_lang = function() { switch_lang((ppa.getCookie("set_lang")=="en")?"th":"en"); }
	var switch_theme = function(chosen, next = true) {
		var expire = new Date(); expire.setTime(expire.getTime() + (365*24*60*60*1000));
		document.cookie = "set_theme="+chosen+";expires="+expire+";path=/";
		if (next) ppa.check_theme(false);
	}
	var fm_uses = function() {
		app.ui.lightbox.open("mid", {title: "Notify material usage", allowclose: true, html: '<b>To control : </b>use <code lang="js" class="language-js">app.ui.notify(a,b);</code><br>&emsp;which contains first parameter <code lang="js" class="language-js">a</code> as the command for <code lang="js" class="language-js">0</code> closes the notification and <code lang="js" class="language-js">1</code> instantiates a new one & the second parameter <code lang="js" class="language-js">b</code> depends on the first parameter as so<br><code lang="js" class="language-js">/* if the first parameter is 0 then the second parameter must be a htmlDOMobject to the message parent */<br>document.querySelector("div.msg")<br>/* if the first parameter is 1 then the second parameter must be an array "[]" which contains two child */<br>/* first one is a number from 0 - 3 which indicates the message priority as { 0: "done (green)",&emsp;1: "info (blue)",&emsp;2: "warn (yellow)",&emsp;3: "error (red)"} */<br>/* and the second one is the message contained in the <i>new</i> notification (must be a STRING) */</code><br>Please note that all notification will automatically close 45 seconds after their instantiation<br><br><b>Structure example : </b><code lang="js" class="language-js">app.ui.notify(1, [0, "Your changes has been saved"]);</code>&emsp;<button onClick="app.ui.notify(1,[0,\'Your changes has been saved\'])">Try</button>'});
	}
	var fm = function(mode, dat) {
		var fm_obj = $("html body aside.fm");
		if (!mode) {
			$(dat).fadeOut(500, function(){
				$(dat).css("filter", "opacity(0)"); $(dat).css("display", "block"); $(dat).css("min-height", "0px");
				$(dat).animate({ height: 0, padding: 0 }, 250, "swing", function(){
					$(dat).remove();
				});
			});
		} else if (mode) {
			var me = $('<div class="msg '+ppa.fm_clrsrc[dat[0]]+'"><div onClick="app.ui.notify(0,this.parentNode)" class="ripple-click">⨯</div><img src="/resource/images/fm-'+ppa.fm_clrsrc[dat[0]]+'.svg"><label>'+dat[1]+'</label></div>');
			fm_obj.append(me);
			me.data("timeout", { x : setTimeout(function(){ fm(0, me); }, 45000) });
			me.height(me.children().last().height()>60?me.children().last().height():60);
			fm_obj.animate({ scrollTop: fm_obj.height() }, 250);
			setTimeout(ppa.ripple_click_program, 500);
		}
	}
	var goto_page = function(path, newtab = false, top_win = false, event) {
		if (top_win) { parent.document.location = "/" + path; }
		else if (ppa.ctrling || newtab) { window.open("/" + path); }
		else { window.location = "/" + path; }
		smooth_scrolling(event);
	}
	var confirm_action = function(act, dat={}) {
		switch(act) {
			case "leave": $(window).bind('beforeunload', function(){ return ""; }); break;
			case "download":
				if (!confirm('Confirm your download of "'+dat.app_name+'"')) {
					$(dat.me).attr("data-href", $(dat.me).attr("href")); $(dat.me).removeAttr("href");
					setTimeout(function() {
						$(dat.me).attr("href", $(dat.me).attr("data-href")); $(dat.me).removeAttr("data-href");
					}, 250);
				}
				; break;
			case "sbmt_frm": return confirm("Please recheck your form data, you won't be able to come back and edit it later.\nAre you sure you want to submit the form now?"); break;
			case "next": break;
		}
	}
	var md_var = {showing: false, cfx: null}, lb_var = {showing: false};
	var md_uses = function() {
		app.ui.lightbox.open("mid", {title: "ModalBox material usage", allowclose: true, html: '<b>To open : </b>use <code lang="js" class="language-js">app.ui.modal.open(a,b);</code><br>&emsp;which contains the first parameter <code lang="js" class="language-js">a</code> as the message at the top of the box STRING typed <code lang="js" class="language-js">""</code> & the second parameter <code lang="js" class="language-js">b</code> as an JS object <code lang="js" class="language-js">{}</code> which contains attributes as so<br><code lang="js" class="language-js">/* for a normal action button */<br>name: "action button name"<br>href: "URL redirection or a javascript:fx call"<br><br>/* for a selectable options */<br>response: "confirm" /* a fixed attribute */<br>option: ["array","of","option","names"] /* name for each action buttons */<br>values: ["value","for","each","options"] /* which has to match the order of the option array (values can be both INT and STRING) */<br><br>/* for text inputs */<br>response: "string" /* a fixed attribute */<br>type: "input element type attribute value" /* eg: "text", "password", "number", "tel", "email", "url" */<br><br>/* for any initiation with response attribute */<br>cfx: function(res){ "callback function" } /* a function called after the options are chosed (a function must contain only 1 parameter for the value of the selected option) */</code><br><br><b>To close : </b>normally, after an interaction, a programmatically close is not required for the modal will automatically close itself, but if it was so, then use <code lang="js" class="language-js">app.ui.modal.close();</code>'});
	}
	var md_open = function(txt, act) {
		if (!md_var.showing && txt!="" && typeof act !== "undefined") {
			$("html body section.modal").attr("show", "true");
			$("html body section.modal span.ctxt").text(txt);
			if (act.response==undefined) {
				$("html body section.modal div span:last-child a:last-child").attr("data-text", act.name);
				$("html body section.modal div span:last-child a:last-child").attr("href", act.href ?? "javascript:app.ui.modal.close()");
			} else {
				if (act.response=="confirm") {
					let i, s = ""; for (i = 0; i<act.option.length-1; i++) s += '<a role="button" data-text="'+act.option[i]+'" href="javascript:app.ui.modal.confirm('+(typeof act.values[i] === "number"? act.values[i].toString():"'"+act.values[i]+"'")+')"></a>'; $("html body section.modal div span:last-child").prepend($(s));
					$("html body section.modal div span:last-child a:last-child").attr("data-text", act.option[i]);
					$("html body section.modal div span:last-child a:last-child").attr("href", "javascript:app.ui.modal.confirm("+(typeof act.values[i] === "number"? act.values[i].toString():"'"+act.values[i]+"'")+")");
				} else if (act.response=="string") {
					$("html body section.modal span.ctxt").text(txt);
					$("html body section.modal div span:last-child a:last-child").attr("data-text", "Submit");
					$("html body section.modal div span:last-child a:last-child").attr("href", "javascript:app.ui.modal.submit()");
					$("html body section.modal span.ctxt").append($('<input name="modal-response" type="'+act.type+'" />'));
					setTimeout(function() { $('html body section.modal span.ctxt input[name="modal-response"]').focus(); }, 1250);
				}
				md_var.cfx = act.cfx;
			} ppa.ripple_click_program();
			md_var.showing = true;
		}
	}
	var md_close = function() {
		var mwf = $("html body section.modal");
		mwf.fadeOut(500, function() {
			mwf.removeAttr("show"); mwf.removeAttr("style");
			$("html body section.modal span.ctxt").html("");
			document.querySelector("html body section.modal div span:last-child").innerHTML = '<a role="button" class="filled"></a>';
		});
		md_var.showing = false;
	}
	var md_confirm = function(chose) { md_close(); md_var.cfx(chose); md_var.cfx = null; }
	var md_send = function() { md_close(); md_var.cfx($('html body section.modal span.ctxt input[name="modal-response"]').val()); md_var.cfx = null; }
	var lb_uses = function() {
		app.ui.lightbox.open("mid", {title: "LightBox material usage", allowclose: true, html: '<b>To open : </b>use <code lang="js" class="language-js">app.ui.lightbox.open(a,b);</code><br>&emsp;which contains the first parameter <code lang="js" class="language-js">a</code> as the position of the lightbox that can only be <code lang="js" class="language-js">"top"</code>, <code lang="js" class="language-js">"mid"</code> or <code lang="js" class="language-js">"btm"</code> (top middle bottom) & the second parameter <code lang="js" class="language-js">b</code> as a JS object <code lang="js" class="language-js">{}</code> which contains attributes as so<br><code lang="js" class="language-js">title: "Heading of the lightbox"<br>allowclose: true|false /* true allows user to force close with an "⨯" at the top right corner of the lightbox and false don\'t */<br>autoclose: integer|decimal /* the lightbox will automatically close after the inputted second(s) has passed */<br>html: \'HTML STRING\' /* what will be shown in the content part of lightbox */</code><br>&emsp;Please note that all of these attributes are optional, but it\'s not a good idea to leave <code lang="js" class="language-js">html</code> as a blank attribute, and be careful if you\'re leaving the <code lang="js" class="language-js">allowclose</code> blank|false it may cause the lightbox to not being able to close until the page reloads so maybe you want to add the attribute <code lang="js" class="language-js">autoclose</code> for safety<br><br><b>To close : </b>regularly users will be the ones to close it or the timeout set, but if you want it to be programmatically close, then use <code lang="js" class="language-js">app.ui.lightbox.close();</code>'});
	}
	var lb_open = function(at, dat) {
		if (!lb_var.showing) {
			$("html body section.lightbox").attr("ding", at);
			var content = $('<div class="content"></div>');
			if (dat.title!==undefined||dat.allowclose) content.append('<div class="head"><span class="txtoe">'+(dat.title!==undefined?dat.title:"")+'</span>'+(dat.allowclose?'<label onClick="app.ui.lightbox.close()">⨯</label>':"")+'</div>');
			if (dat.html!==undefined) content.append('<div class="body">'+dat.html+'</div>');
			$("html body section.lightbox div.displayer").append(content);
			if (dat.autoclose!==undefined) lb_var.autoclose = setTimeout(function() { app.ui.lightbox.close(); }, dat.autoclose+1250);
			disableScroll(); ppa.color_up_codes(); // $("html body").css("overflow", "hidden");
			lb_var.showing = true;
		}
	}
	var lb_close = function() {
		var mwf = $("html body section.lightbox");
		mwf.attr("ded", mwf.attr("ding")); mwf.removeAttr("ding");
		clearTimeout(lb_var.autoclose); setTimeout(function() {
			mwf.removeAttr("ded");
			$("html body section.lightbox div.displayer").html("");
			enableScroll(); // $("html body").removeAttr("style");
			lb_var.showing = false;
		}, 1500);
	}
	var scrollToTop = function() { $("html, html body").animate({ scrollTop: 0 }, 500, "swing"); }
	var notifperm = function(r=false) {
		if (!("Notification" in window)) app.ui.notify(1, [3, "Your browser doesn't support desktop notification"]);
		else if (Notification.permission !== "denied") Notification.requestPermission().then(function (perm) {
			if (typeof r === "object") {
				SendDeskNoti(r);
				return (Notification.permission === "granted");
			}
		}); else app.ui.notify(1, [1, "Notification permission is already satisfied"]);
	}
	var notification = function(data) {
		if (Notification.permission === "granted") SendDeskNoti(data);
		else if (!notifperm(data)) app.ui.notify(1, [3, "We're unable to send you the notification for the permission is not granted"]);
	}
	var SendDeskNoti = function(data) {
		var noti = new Notification(app_name, {
			body: data.context,
			icon: (data.option!==undefined&&data.option.icon!==undefined) ? data.option.icon : "/favicon.ico",
			dir: (data.option!==undefined&&data.option.dir!==undefined) ? data.option.dir : "ltr",
			image: (data.option!==undefined&&data.option.image!==undefined) ? data.option.image : undefined
		});
		if (data.onclick!==undefined) noti.onclick = data.onclick;
		if (data.option!==undefined&&data.option.timeout!==undefined) setTimeout(function() { noti.close(); }, data.option.timeout*1000);
	}
	var share_now = function() {
		if (navigator.share) {
			navigator.share({
				title: document.querySelector('html head meta[name="description"]').content+" ["+document.title+"]",
				url: location.href
			}).then(function() {
				// app.ui.notify(1, [1, "Thanks for sharing"]);
			});
		} else {
			app.ui.lightbox.open("mid", {title: "Share", allowclose: false, html: '<div class="sharer"><table><tbody><tr><td><a role="button" class="gray" href="javascript:app.io.share.to(\'facebook\')"><img src="/resource/images/nav-share-facebook.png"><span>Facebook</span></a></td><td><a role="button" class="gray" href="javascript:app.io.share.to(\'twitter\')"><img src="/resource/images/nav-share-twitter.png"><span>Twitter</span></a></td></tr><tr><td><a role="button" class="gray" href="javascript:app.io.share.to(\'googleplus\')"><img src="/resource/images/nav-share-google_plus.png"><span>Google+</span></a></td><td><a role="button" class="gray" href="javascript:app.io.share.to(\'linkedin\')"><img src="/resource/images/nav-share-linkedin.png"><span>LinkedIn</span></a></td></tr><tr><td><a role="button" class="gray" href="javascript:app.io.share.to(\'email\')"><i class="material-icons">email</i><span>Email</span></a></td><td><a role="button" class="gray" href="javascript:app.io.share.to(\'copyurl\')"><i class="material-icons">link</i><span>Copy URL</span></a></td></tr></tbody></table><center><button class="red" onClick="app.io.share.close()">Cancel</button></center></div>'});
			$("html body nav.cm ul.social li.share").attr("disabled", "");
		}
	}
	const social_platforms = {
		facebook: "facebook.com/sharer/sharer.php?u=",
		twitter: "twitter.com/share?url=",
		googleplus: "plus.google.com/share?url=",
		linkedin: "linkedin.com/shareArticle?mini=true&url=",
		email: ""
	}; const platforms = Object.keys(social_platforms);
	var share_to = function(platform) {
		if (platforms.includes(platform)) {
			app.io.share.close();
			var url = "https://"+social_platforms[platform], loc = encodeURI(location.href), desc = encodeURI(document.querySelector('html head meta[name="description"]').content);
			switch (platform) {
				case platforms[0]: url += loc; break;
				case platforms[1]: url += loc+"&text="+encodeURI(document.title+"\n")+desc+encodeURI("\n"); break;
				case platforms[2]: url += loc; break;
				case platforms[3]: url += loc+"&title="+encodeURI(document.title)+"&summary="+desc; break;
				case platforms[4]: url = "mailto:?subject="+encodeURI(document.title)+"&body="+desc+encodeURI("\n")+loc; break;
			} open(url);
		} else if (platform=="copyurl") { app.io.share.close(); app.io.copy.location(); }
	}
	var share_close = function() {
		app.ui.lightbox.close();
		$("html body nav.cm ul.social li.share").removeAttr("disabled");
	}
	return {
		ui: {
			toggle: {
				langes: toggle_lang,
				navtab: function() { toggle_uibtn("navtab"); }
			}, change: {
				lang: function(a) { switch_lang(a); },
				theme: function(a) { switch_theme(a); }
			}, usage: {
				modal: md_uses,
				lightbox: lb_uses,
				notify: fm_uses
			}, modal: {
				open: function(a,b) { md_open(a,b); },
				close: md_close,
				confirm: function(a) { md_confirm(a); },
				submit: md_send
			}, lightbox: {
				open: function(a,b) { lb_open(a,b); },
				close: lb_close
			}, notify: function(a,b) { fm(a,b); }
		}, io: {
			warpto: function(a,b=false,c=false,event) { goto_page(a,b,c,event); },
			confirm: function(a,b={}) { confirm_action(a,b); },
			copy: {
				location: copy_page_url
			}, notify: {
				permission: notifperm,
				send: function(a) { notification(a); }
			}, share: {
				now: share_now,
				to: function(a) { share_to(a); },
				close: share_close
			}, scrollToTop: scrollToTop
		}
	};
}
var app = initial_app();