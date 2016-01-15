Number.prototype.formatTime = function (forceMinutes, forceHours) {
    var i = Math.abs(parseInt(this));
    var s = parseInt(i % 60);
    var m = parseInt((i / 60) % 60);
    var h = parseInt((i / 3600) % 60);
    var v = '';
    if (forceHours || h > 0) {
        v += h + ':';
    }
    if (v || forceMinutes || m > 0) {
        if (v && m < 10) {
            v += '0';
        }
        v += m + ':';
    }
    if (v && s < 10) {
        v += '0';
    }
    v += s;
    if (this < 0) {
        v = '<span class="negative">-' + v + '<span>';
    }
    return v;
};
Number.prototype.formatNumber = function () {
    var a = Math.abs(this).toString().split('');
    v = '';
    var n = 0;
    while (a.length > 0) {
        if (n > 0 && n % 3 == 0)
            v = '.' + v;
        v = a.pop() + v;
        n++;
    }
    if (this < 0) {
        v = '<span class="negative">-' + v + '<span>';
    }
    return v;
};
