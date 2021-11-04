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

window.onload = function() {
	kw_chm_reload(KW_G_CHM_INIT, KW_G_CHM_ORDER);
}

