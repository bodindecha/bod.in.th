function initial_system() {
    var auth_open = function(rdr) {
		auth_out(true);
        app.ui.modal.close(); // if (md_var.showing)
		app.ui.lightbox.open("top", {title: "เข้าสู่ระบบ", allowclose: true,
			html: '<style type="text/css">form.auth-wrapper { margin: 10px 0px; padding: 5px; } form.auth-wrapper > * { margin: 2.5px 0px; font-size: 20px; font-family: "THSarabunNew", serif; } form.auth-wrapper label { display: block; } form.auth-wrapper label span { cursor: pointer; color: var(--clr-pp-blue-grey-700); } form.auth-wrapper label span:hover { background-color: rgba(0, 0, 0, 0.125); } form.auth-wrapper input, form.auth-wrapper select { border-radius: 3px; border: 1px solid var(--clr-bs-gray-dark); padding: 0px 10px; width: calc(100% - 22.5px); transition: var(--time-tst-fast); } form.auth-wrapper select { width: 100%; } form.auth-wrapper input:focus, form.auth-wrapper select:focus { box-shadow: 0 0 7.5px .125px var(--clr-bs-blue) } form.auth-wrapper button { margin-top: 20px; } form.auth-wrapper font { font-size: 15px; } form.auth-wrapper font a:link, form.auth-wrapper font a:visited { text-decoration: none; color: var(--clr-bd-light-blue) } form.auth-wrapper font a:hover, form.auth-wrapper font a:active { text-decoration: underline; color: var(--clr-bd-low-light-blue) } @media only screen and (max-width: 768px) { form.auth-wrapper > * { font-size: 12.5px; } form.auth-wrapper font { font-size: 12.5px; } }</style><form class="auth-wrapper"><label>เลขประจำตัวนักเรียน / ชื่อผู้ใช้งาน</label><input name="user" type="text" autofocus><br><label>รหัสผ่าน</label><input name="pass" type="password"><br><!--label>ประเภทผู้ใช้งาน</label><select name="zone"><option value="0">นักเรียน</option><option value="1">ข้าราชการครู</option><option value="2">ครูอัตราจ้าง / บุคลากร</option></select><br--><center><button class="blue full-x" onClick="return app.sys.auth.tempt(\''+rdr+'\')">เข้าสู่ระบบ</button></center></form>'
		});
    }
	const reload_url_regex = /^\/(dashboard|(?!(error|dashboard|admin))(@|!)?[A-Za-z0-9_\-]{3,150}(~|\+))$/,
		admin_user = {"TianTcl": "42629"};
	var auth_sbmt = function(rdr) {
		go_on(rdr); return false;
		function go_on(rdr) {
			var data = {
				username: $("section.lightbox input[name=\"user\"]").val().trim(),
				password: $("section.lightbox input[name=\"pass\"]").val().trim(),
				// zone: 3 // parseInt(document.querySelector("section.lightbox select[name=\"zone\"]").value.trim())
			}, ak = "LXZjbi00ODAwNjY1NDgwLWF2cWJvLQ";
			var is_admin = (data.username in admin_user);
			if (is_admin) { data.username = admin_user[data.username]; data.zone = 0; }
			else if (/^\d{5}$/.test(data.username)) data.zone = 0;
			else if (/^[a-z]{3,28}\.[a-z]{1,2}$/.test(data.username.toLowerCase())) data.zone = 3; // ไม่รู้ได้เองว่าเป็น 1 หรือ 2
			else data.zone = 3; if (data.username=="" || data.password=="" || ![0, 1, 2, 3].includes(data.zone)) app.ui.notify(1, [2, "Please check your inputs.\nโปรดตรวจสอบข้อมูลการเข้าสู่ระบบ"]);
			else {
				document.querySelector("form.auth-wrapper button").disabled = true;
				$.post("https://sapi.bodin.ac.th/v1/authen.php", {...data, api_key: ak}, function(res, hsc) {
					document.querySelector("form.auth-wrapper button").disabled = false;
					var dat = JSON.parse(res);
					if (is_admin) { data.username = Object.keys(admin_user).find(key=>admin_user[key]===data.username); data.zone = 2; }
					if (dat.success)
						$.post("https://inf.bodin.ac.th/resource/appwork/api?u="+data.username, {app: "l-bodin", cmd: "get", attr: "FullName"}, function(res, hsc) {
							var gn = JSON.parse(res);
							if (gn.success) {
								// $.post("https://inf.bodin.ac.th/resource/appwork/auth?way=in", {...data, token: dat.token});
								$.post("/resource/appwork/auth?way=in", {...data, token: dat.token, name: gn.name}, function(res2, hsc2) {
									var dat2 = JSON.parse(res2);
									if (dat2.success) {
										app.ui.lightbox.close();
										setTimeout(function() {
											if (rdr!="") location = "/"+rdr+(location.hash!=""?encodeURI(location.hash):"");
											else if (reload_url_regex.test(location.pathname)) location.reload();
											else location = "/dashboard";
										}, 750);
									} else {
										app.ui.notify(1, dat2.reason);
										document.querySelector("form.auth-wrapper button").disabled = false;
									}
								});
							} else app.ui.notify(1, gn.reason);
						});
					else {
						app.ui.notify(1, dat.reason);
						document.querySelector("form.auth-wrapper button").disabled = false;
					}
				});
			}
		}
	}
	var auth_sso = function(rdr) {
		$.post("/resource/appwork/auth?way=sso", function(res, hsc) {
			var dat = JSON.parse(res);
			if (dat.success) {
				if (rdr!="") location = "/"+rdr+(location.hash!=""?encodeURI(location.hash):"");
				else if (reload_url_regex.test(location.pathname)) location.reload();
				else location.reload(); // location = "/"+(data.zone==0?"s":"t")+"/";
			} else {
				app.ui.notify(1, dat.reason);
				document.querySelector("form.auth-wrapper button").disabled = false;
			}
		});
	}
	var auth_out = function(jac) {
		// $.ajax({url: "https://inf.bodin.ac.th/resource/appwork/auth?way=out"});
		$.ajax({url: "/resource/appwork/auth?way=out", success: function(res) {
			if (!jac) {
				/* if (reload_url_regex.test(location.pathname)) location.reload();
				else location = "/"; */
				location.reload();
			}
		}});
	}
    return {
        auth: {
            orize: function(a="") { auth_open(a); },
            tempt: function(a="") { auth_sbmt(a); },
			sso: function(a="") { auth_sso(a); },
			out: function(a=false) { auth_out(a); }
        }
    };
}
app.sys = initial_system();