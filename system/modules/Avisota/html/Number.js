Number.prototype.formatTime = function() {
	var i = Math.abs(parseInt(this));
	var s = parseInt(i % 60);
	var m = parseInt((i / 60) % 60);
	var h = parseInt((i / 3600) % 60);
	var v = (h>0 ? h + ':' : '') + ((m<10 ? '0' : '') + m + ':') + ((s<10 ? '0' : '') + s);
	if (this < 0) {
		v = '<span class="negative">-' + v + '<span>';
	}
	return v;
};
Number.prototype.formatNumber = function() {
	var a = Math.abs(this).toString().split('');
	v = '';
	var n = 0;
	while (a.length > 0) {
		if (n>0 && n%3 == 0)
			v = '.' + v;
		v = a.pop() + v;
		n ++;
	}
	if (this < 0) {
		v = '<span class="negative">-' + v + '<span>';
	}
	return v;
};
