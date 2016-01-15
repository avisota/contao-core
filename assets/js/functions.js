function index_check(arrays, index) {
    for (var i = 0; i < arrays.length; i++) {
        if (index < arrays[i].length) return true;
    }
    return false;
}

function equalize(arrays) {
    var index = 0;

    while (index_check(arrays, index)) {
        var modified = false;
        for (var i = 0; i < arrays.length; i++) {
            var j = (i + 1) % arrays.length;
            var a = arrays[i][index];
            var b = arrays[j] ? arrays[j][index] : false;
            if (a && (!b || a[0] < b[0])) {
                arrays[j].splice(index, 0, [a[0], index > 0 ? arrays[j][index - 1][1] : 0]);
                modified = true;
            }
        }
        if (!modified) {
            index++;
        }
    }
}

function getRangeIndex(timespan) {
    // 1 month (more then 1 week)
    if (timespan >= 604800) {
        return 7;
    }
    // 1 week (more than 3 days)
    else if (timespan >= 259200) {
        return 6;
    }
    // 3 days (more than 2 days)
    else if (timespan >= 172800) {
        return 5;
    }
    // 2 days (more than 1 day)
    else if (timespan >= 86400) {
        return 4;
    }
    // 1 day (more than 12 hours)
    else if (timespan >= 43200) {
        return 3;
    }
    // 12 hours (more then 6 hours)
    else if (timespan >= 21600) {
        return 2;
    }
    // 6 hours (more than 1 hour)
    else if (timespan >= 3600) {
        return 1;
    }
    else {
        return 0;
    }
}
