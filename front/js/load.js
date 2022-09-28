function kw_chm_reload(a, o) {
	const cols = 3;
	let tds = qsa('td');
	for (let i=0; i < o.length; i++) {
		let e = tds.item(i * cols);
		let v = a[o[i]];
		e.innerHTML = v;
		continue;
	}
	
	byid('asof').innerHTML = a['asof'];
	
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
		byid('histNIST').innerHTML = rj.nistallHT;
		byid('NISTIPb' ).innerHTML = rj.nistHTIP;
	}
	xm.send();
	
}