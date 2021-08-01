function go(get = null) {
    var from_form = (get==null);
    if (from_form) get = $("html body div.container form.jumpto input").val();
    get = get.trim();
    if (get=="") app.ui.notify(1, [1, "Please enter some information"]);
    else {
        get = get.replace(/^(http(s)?:\/\/)?bod\.in\.th\//, "");
        if (/^(?!(error|dashboard|admin))(@|!)?[A-Za-z0-9_\-]{3,150}$/.test(get)) window.open("/"+get+"?utm_source=dash&utm_campaign=paste");
        else app.ui.notify(1, [3, "Invalid information format"]);
    } if (from_form) return false;
}
function profile(m,e) {
    var viewurl = "https://inf.bodin.ac.th/"+m.innerText;
    /* if (e.ctrlKey) window.open(viewurl, "_blank");
    else app.ui.lightbox.open("mid", {title: "โปรไฟล์ของฉัน", allowclose: true, html: '<iframe src="'+viewurl+'" style="width:90vw;height:80vh;border:none">Loading...</iframe>'}); */
    window.open(viewurl, "_blank");
}
function random_short_url() {
    $('input[name="csu"]').val(Date.now().toString(36));
    validate_input();
}
var val_url = false, val_csu = false;
const val = {myd: ["go.bschool.me", "l.tiantcl.net", "301sa.ga", "l.bodin.ac.th", "go.bodin.ac.th", "bod.in.th"],
    bld: ["gg.gg", "goo.gl", "goo.gle", "bit.ly", "tiny.cc", "tinyurl.com", "url.dev", "temporary-url.com", "ow.ly", "is.gd", "buff.ly", "adf.ly", "bit.do", "mcaf.ee", "moourl.com", "page.link", ".page.link", "bl.ink", "demo.polr.me", "t2m.io", "shor.by", "oe.cd", "vbly.us", "utm.io", "psbe.co", "rebrand.ly", "po.st", "branch.io", "short.cm", "snip.li", "shorte.st", "snip.ly", "ity.im", "cur.lv", "q.gs", "po.st", "bc.vc", "twitthis.com", "u.to", "j.mp", "buzurl.com", "cutt.us", "u.bb", "x.co", "prettylink.com", "prettylinkpro.com", "scrnch.me", "filoops.info", "vzturl.com", "qr.net", "1url.com", "tweez.me", "v.gd", "tr.im", "zip.net", "tinyarrows.com", "adcraft.co", "adcrun.ch", "adflav.com", "aka.gr", "bee4.biz", "cektkp.com", "dft.ba", "fun.ly", "fzy.co", "gog.li", "golinks.co", "hit.my", "id.tl", "linkto.im", "lnk.co", "nov.io", "p6l.org", "picz.us", "shortquik.com", "su.pr", "sk.gy", "tota2.com", "xlinkz.info", "xtu.me", "yu2.it", "zpag.es", "megaurl.it", "enlar.gr", "rotf.lol", "tiny.one", "ipst.me", "link2.cyou", "t.co", "lnkd.in", "db.tt", "qr.ae", "git.io", "t.ly"],
    niw: ["naked", "sex", "nude", "fuck", "dick", "pubic", "bitch", "clit", "vagina"],
    wld: ["fb.me", "m.me", "line.me", ".app.goo.gl"]
}; // u.to j.mp u.bb x.co v.gd t.co
function resize_view_table() { document.querySelectorAll("html body main div.container div.lists div.viewport").forEach((et) => {
    $(et).css("--h", $(et).children().last().outerHeight().toString()+"px");
}); }
function fd(w=1) { w += 3;
    var txt = $("div.lists:nth-child("+w.toString()+") div.viewport div.action div.filter input").val().trim();
    w3.filterHTML("div.lists:nth-child("+w.toString()+") div.viewport div.viewer table tbody", "tr", txt); resize_view_table();
}
function rovt(wc, w=1) { w += 3;
    w3.sortHTML("div.lists:nth-child("+w.toString()+") div.viewport div.viewer table tbody", "tr", "td:nth-child("+wc.toString()+")");
}
// URL list view function
function copy(me) {
    const ce = document.createElement("textarea"); ce.value = location.hostname+"/"+$(me.parentNode).children().first().text();
    document.body.appendChild(ce); ce.select(); document.execCommand("copy"); document.body.removeChild(ce);
    app.ui.notify(1, [0, "Short URL copied!"]);
}
function ViewAnalystic(m,e) {
    var viewurl = "/"+m+"+";
    if (e.ctrlKey) window.open(viewurl, "_blank");
    else app.ui.lightbox.open("mid", {title: "Analystics of \""+m+"\"", allowclose: true, html: '<iframe src="'+viewurl+'" style="width:90vw;height:80vh;border:none">Loading...</iframe>'});
}
function change_status(em) {
    const me = em.parentNode;
    var keyword = $(me.parentNode).children().first().text().split(" ")[0], curstatus = me.innerText.split(" ")[0], newstatus, newtxt;
    switch (curstatus) {
        case "Active": newstatus = "N"; newtxt = "Disabled"; break;
        case "Disabled": newstatus = "Y"; newtxt = "Active"; break;
        default: newstatus = ""; newtxt = ""; break;
    } if (newstatus != "" && newtxt != "") $.post("/resource/appwork/override", {
        cmd: "change",
        attr: newstatus,
        target: keyword
    }, function (res, hsc) {
        var dat = JSON.parse(res);
        app.ui.notify(1, dat.reason);
        if (dat.success) me.innerHTML = newtxt+' <a onClick="change_status(this)" href="javascript:void(0)"><i class="material-icons">power_settings_new</i></a>';
    });
}