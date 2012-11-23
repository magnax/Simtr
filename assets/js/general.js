function decodeRawTime(raw_time) {

    var s = raw_time % 60;
    var r = (raw_time - s) / 60;
    var m = r % 60;
    r = (r - m) / 60;
    var h = r % 24;
    var d = (r - h) / 24;
    var y = Math.floor(d / 20);
    var f = d % 20;
    return y + '-' + f + ' (' + d + ') ' + ('0'+h).substr(-2) + ':' + ('0' + m).substr(-2) + ':'  + ('0' + s).substr(-2);
}