function byid(id) { const e = document.getElementById(id); return e; }
function kwas(v, msg) {
	if (!v) {
		if (!msg) msg = 'unknown message';
		throw msg;
	}
}
function time()  { return (new Date().getTime()); } 
function qsa (s) { return document.querySelectorAll(s); }