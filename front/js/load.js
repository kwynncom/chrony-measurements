var KWCHMOTDS = false;

function kw_chm_reload(a, o) {
	const cols = 3;
	let tds = KWCHMOTDS;
	for (let i=0; i < o.length; i++) {
		let e = tds.item(i * cols);
		let v = a[o[i]];
		e.innerHTML = v;
		continue;
	}
	
	inht('asof', a['asof']);
	inht('worstP', a.worstHT);
	
	return;
}

function reload_btn_onclick() {
    const xm = new XMLHttpRequest();
	xm.open('GET', 'load.php?json=1');
	xm.onloadend = function() {
		const rj = JSON.parse(this.responseText);
		kw_chm_reload(rj, KW_G_CHM_ORDER);
		const lht = rj.logs.htrf;
		const e = byid('histb10');
		e.innerHTML = lht;
		inht('histNIST', rj.nistallHT);
		inht('NISTIPb' , rj.nistHTIP);
	}
	xm.send();
	
}

onDOMLoad(function() {
	KWCHMOTDS = byid('ordp').querySelectorAll('td');
	kw_chm_reload(KW_G_CHM_INIT, KW_G_CHM_ORDER);
});
