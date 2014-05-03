/* > String
 * ************************************************************************** */
/**
 * Return the string in plain text
 * @return String in plain text
 */
String.prototype.cleanHTML = function () {
	return this.replace(/<.*?>/g,'');
}

/**
 * Return the string left and right trimmed
 * @return String left and right trimmed
 */
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
 
/**
 * Return the string left trimmed
 * @return String left trimmed
 */
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
 
/**
 * Return the string right trimmed
 * @return String right trimmed
 */
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}

/**
 * Return the string left padded
 * @param padString String for fill the padding spaces (usually one character)
 * @param length Number of padding
 * @return String left padded
 */
String.prototype.lpad = function(padString, length) {
	var str = this;
    while (str.length < length)
        str = padString + str;
    return str;
}

/**
 * Return the string right padded
 * @param padString String for fill the padding spaces (usually one character)
 * @param length Number of padding
 * @return String right padded
 */
String.prototype.rpad = function(padString, length) {
	var str = this;
    while (str.length < length)
        str = str + padString;
    return str;
}

/**
 * Return a string truncate with length and endmark
 * @param len Length to truncate
 * @param endmark The string to add after truncate
 * @return String truncate
 */
String.prototype.truncate = function(len, endmark) {
	if (len == null) len = 25;
	if (endmark == null) endmark = '...';
	if (this.length <= len) return this;
	return this.substring(0, len) + endmark;
}

String.prototype.explode = function (c) {
	var o = new Array();
	var temp = '';
	for (var i=0;i<this.length;i++) {
		if (this[i] == c) {
			o.push(temp);
			temp = '';
		} else {
			temp += this[i];
		}
	}
	o.push(temp);
	return o;
}

/* > Translations
 * ************************************************************************** */
if (typeof js_translations == 'undefined') js_translations = new Array();
String.prototype.localized = function (domain) {
	if (domain == undefined) return this;
	if (js_translations[domain] == undefined) return this;
	if (this in js_translations[domain]) return js_translations[domain][this];
	return this;
}

/* > Dates
 * ************************************************************************** */

/**
 * Calculate the diference betwen two dates and return the result
 * @param end End date
 * @return The diference between dates in Date format
 */

Date.prototype.diff = function (date) {
	var sd = date.getTime();
	var ed = this.getTime();
	var res = new Date();
	res.setTime(ed - sd);
	res.setHours(res.getHours()-1); // TODO Check this line for a better way
	return res;
}

/**
 * Return the result of sum time to the current date
 * @param time Time to add
 * @return Sum of date and time
 */
Date.prototype.addTime = function (time) {
	var d = new Date();
	d.setFullYear(this.getFullYear());
	d.setMonth(this.getMonth());
	d.setDate(this.getDate());
	d.setHours(this.getHours() + time.getHours());
	d.setMinutes(this.getMinutes() + time.getMinutes());
	d.setSeconds(this.getSeconds() + time.getSeconds());
	return d;
}

/**
 * Return true in case the date was between start and end, false in the
 * oposite case
 * @param start Initial date (JS)
 * @param end End date (JS)
 * @return True in case is between, false in the oposite case
 */
Date.prototype.inRange = function (start, end) {
	if (this.getTime() >= start.getTime() && this.getTime() <= end.getTime()) return true;
	return false;
}

/**
 * Set the date from a Date string from MySQL Date
 * @param mysqlDate Date in MySQL string format
 */
Date.prototype.parseMySQLDate = function (mysqlDate) {
	if (typeof(mysqlDate) == 'object') return mysqlDate;
	var t = mysqlDate.split(/[-]/);
	this.setFullYear(t[0]);
	this.setMonth(t[1] - 1);
	this.setDate(t[2]);
}

/**
 * Set the date from a Date string from MySQL Datetime
 * @param mysqlDate Datetime in MySQL string format
 */
Date.prototype.parseMySQLDatetime = function (mysqlDate) {
	if (typeof(mysqlDate) == 'object') return mysqlDate;
	var t = mysqlDate.split(/[- :]/);
	if (!t[5]) t[5] = "00";
	this.setFullYear(t[0]);
	this.setMonth(t[1] - 1);
	this.setDate(t[2]);
	this.setHours(t[3]);
	this.setMinutes(t[4]);
	this.setSeconds(t[5]);
}

/**
 * Set the date from a Date string from MySQL Time
 * @param mysqlDate Time in MySQL string format
 */
Date.prototype.parseMySQLTime = function (mysqlTime, forcedDate) {
	if (typeof(mysqlTime)=='object') return mysqlTime;
	if (forcedDate == null) forcedDate = new Date();
	var t = mysqlTime.split(/[:]/);
	if (!t[2]) t[2] = "00";
	this.setFullYear(forcedDate.getFullYear());
	this.setMonth(forcedDate.getMonth());
	this.setDate(forcedDate.getDate());
	this.setHours(t[0]);
	this.setMinutes(t[1]);
	this.setSeconds(t[2]);
}

/**
 * Return the passed as MySQL Date format string
 * @return MySQL Date format string
 */
Date.prototype.toMySQLDate = function () {
	return this.getFullYear() + "-" + (this.getMonth() + 1).toString().lpad('0', 2) + "-" + this.getDate().toString().lpad('0', 2);
}

/**
 * Return the passed as MySQL Time format string
 * @return MySQL Time format string
 */
Date.prototype.toMySQLTime = function () {
	return this.getHours().toString().lpad('0', 2) + ":" + this.getMinutes().toString().lpad('0', 2) + ":" + this.getSeconds().toString().lpad('0', 2);
}

/**
 * Return the passed as MySQL Datetime format string
 * @return MySQL Datetime format string
 */
Date.prototype.toMySQLDatetime = function () {
	return this.getFullYear() + "-" + (this.getMonth() + 1).toString().lpad('0', 2) + "-" + this.getDate().toString().lpad('0', 2) + " " + this.getHours().toString().lpad('0', 2) + ":" + this.getMinutes().toString().lpad('0', 2) + ":" + this.getSeconds().toString().lpad('0', 2);
}

Date.prototype.toTwitterTime = function (a) {
	var b = new Date();
    var c = typeof a == "date" ? a : new Date(a);
    var d = b - c;
    var e = 1000, minute = e * 60, hour = minute * 60, day = hour * 24, week = day * 7;
    if (isNaN(d) || d < 0) { return "" }
    if (d < e * 7) { return "Right now" }
    if (d < minute) { return Math.floor(d / e) + " secs ago" }
    if (d < minute * 2) { return "about 1 min ago" }
    if (d < hour) { return Math.floor(d / minute) + " mins ago" }
    if (d < hour * 2) { return "about 1 hour ago" }
    if (d < day) { return Math.floor(d / hour) + " hours ago" }
    if (d > day && d < day * 2) { return "yesterday" }
    if (d < day * 365) { return Math.floor(d / day) + " days ago" } else { return "Over a year ago" }
}

/* > Numbers
 * ************************************************************************** */

/**
 * Return false if number is 0 and true in the other case
 * @return false if is 1 and true in other case
 */
Number.prototype.toBool = function () {
	if (this > 0) return true;
	return false;
}

/**
 * Return bytes into a human readable text
 * @param precision Number of decimal places
 * @return The size in human readable text
 */
Number.prototype.bytesToSize = function (precision) {
	if (precision == null) precision = 2;
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;
   
	if ((this >= 0) && (this < kilobyte)) return this + ' B';
	else if ((this >= kilobyte) && (this < megabyte)) return (this / kilobyte).toFixed(precision) + ' KB';
	else if ((this >= megabyte) && (this < gigabyte)) return (this / megabyte).toFixed(precision) + ' MB';
	else if ((this >= gigabyte) && (this < terabyte)) return (this / gigabyte).toFixed(precision) + ' GB';
	else if (this >= terabyte) return (this / terabyte).toFixed(precision) + ' TB';
	else return this + ' B';
}

/* > Array
 * ************************************************************************** */

/**
 * Return true if the element is in the array, false in the other case
 * @return true if the element is in the array, false in the other case
 */
Array.prototype.exists = function (element) {
	for (var i=0;i<this.length;i++)
		if (element === this[i]) return true;
	return false;
}

Array.prototype.remove = function (element) {
	var o = new Array();
	for (var i=0;i<this.length;i++)
		if (element != this[i]) o.push(this[i]);
	return o;
}

Array.prototype.existsId = function (id) {
	for (var i=0;i<this.length;i++)
		if (id == this[i].id) return true;
	return false;
}

Array.prototype.getItemId = function (id) {
	for (var i=0;i<this.length;i++)
		if (id == this[i].id) return this[i];
	return null;
}

Array.prototype.removeItemId = function (id) {
	var o = new Array();
	for (var i=0;i<this.length;i++)
		if (id != this[i].id) o.push(this[i]);
	return o;
}

//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com/array/shuffle [v1.0]
Array.prototype.shuffle = function() {
	for(var j, x, i = this.length; i; j = parseInt(Math.random() * i), x = this[--i], this[i] = this[j], this[j] = x);
};

