/**
 * basic javascript functions for bling bling magic on frontpage
 *
 * @author Andreas Demmer <mail at andreas-demmer dot de>
 */

/*
 * detected browser
 */
var browser;

/*
 * detected browser version
 */
var browserVersion;

/*
 * is detected browser an Internet Exploder
 */
var isIE;

/*
 * indicates whether the screenshots have been loaded previously
 */
var screenshotGalleryInitialized = 0;

/*
 * indicates whether the reflections have been loaded previously
 */
var reflectionsInitialized = 0;

/*
 * makes the html search form a fancy js dropdown
 */
transformSearchbox = function () {
	if (isIE && browserVersion == 6) {
		return;
	}

	$('#search form span').remove();
	$("#search form select").css('width', '30px');
	$("#search form select").selectBox({css:'select'});
	$("#search form div:first").addClass('searchbox');

	var searchLabel = $("#search form div p").html();

	$("#search form input.textfield").attr('value', searchLabel);
	$("#search form input.textfield").addClass('transformed');
	$("#search form input.textfield").addClass('hint');
	$("#search form input.textfield").click(function () {
		$(this).removeClass('hint');
		$(this).attr('value', '');
	});

	$("#search form div.select p").html('&nbsp;');
	$("#search form div.select p").addClass('green');

	$("#search form .button").css('display', 'none');
	$("#search form .searchbutton").css('display', 'inline');

	$("#search form li").not("li:contains('oftware')").addClass('green');
	$("#search form li:contains('oftware')").addClass('orange');
	$("#search form li:contains('logiciels')").addClass('orange');
}

/*
 * preselects the current language in language dropdown
 * and set the form to auto-transmit on change
 */
transformLanguageSelection = function () {
	var currentLanguageAbbrev = $('#i18n option:first').attr('value');

	$('#i18n option:first').remove();
	$('#i18n option:first').remove();

	$('#i18n option[value=' + currentLanguageAbbrev + ']').attr('selected', 'selected');

	$('#i18n #language').change(function () {
		$('#i18n form:first').submit();
	});
}

/*
 * detect browser and version
 */
browserDetection = function () {
	browser = navigator.userAgent.toLowerCase();
	browserVersion = parseFloat( browser.substring( browser.indexOf('msie ') + 5 ) );
	isIE = ( (browser.indexOf("msie") != -1) && (browser.indexOf("opera") == -1) && (browser.indexOf("webtv") == -1));
}

/*
 * compare two numbers and return true if second is bigger, false if otherwise
 */
numberComparisonDescending = function (a, b)	{
	return b-a;
}

/*
 * make "get", "discover" and "create" box the same height
 */
equalizeQuickstartBoxes = function () {
	/* IE does not support this (wow, how unexpected!) */
	if (isIE) return;

	var height1 = $('#get a .buttontext').height().replace('px', '');
	var height2 = $('#discover a .buttontext').height().replace('px', '');
	var height3 = $('#create a .buttontext').height().replace('px', '');

	var heights = new Array(height1, height2, height3);
	heights.sort(numberComparisonDescending);

	$('.buttontext').height(heights[0] + 'px');
}

/**
 * fetch screenshots from JSON service and add markup to DOM
 */
initializeGallery = function (data) {
	var markup;

	$('#screenshots').html('<ul id="screenshot_gallery" class="jcarousel-skin-opensuse"></ul>');

	/* convert json string to markup and write to DOM */
	$.each(data, function(i, screenshot) {
		markup = '<li><a href="' + screenshot.file_large + '" title="' + screenshot.description + '" rel="group1"><img src="' + screenshot.file_small + '" width="200" height="150" /></a></li>';
		$('#screenshot_gallery').append(markup);
	});

	/* add gallery slider, reflections and ZoomBox */
	$('#screenshot_gallery').jcarousel();
	$("#screenshots a").fancybox({
			'overlayShow': true
	});

	screenshotGalleryInitialized = 1;
}

/*
 * show screenshot container smoothly
 */
showScreenshots = function(){
	$("#toggle_screenshots").blur();

	if (screenshotGalleryInitialized == 0) {
		$.getJSON('../screenshot_gallery/cached_screenshots.json', initializeGallery);
	}

	$('#screenshots').slideToggle('slow', function () {
		if (reflectionsInitialized == 0) {
			$('#screenshot_gallery img').reflect();
			reflectionsInitialized = 1;
		}
	});

	$("#toggle_screenshots").addClass('expanded');
	$("#toggle_screenshots span").html('&uarr; Screenshots');
}

/*
 * hide screenshot container smoothly
 */
hideScreenshots = function(){
	$("#toggle_screenshots").blur();
	$('#screenshots').slideToggle('slow');
	$("#toggle_screenshots").removeClass('expanded');
	$("#toggle_screenshots span").html('&darr; Screenshots');
}

/*
 * processes PNG files with Microsoft filter, only on Internet Explorer 6
 */
fixIE6alphaTransparency = function () {
	if (isIE && browserVersion == 6) {
		$('#quicklinks .top').each(pngfix);
		$('#quicklinks .buttontext').each(pngfix);
		$('#quicklinks .bottom').each(pngfix);
		$('#shop img').each(pngfix);
	}
}

/*
 * autostart function, runs on DOM completion
 */
$(document).ready(function() {
	browserDetection ();
	fixIE6alphaTransparency ();
	equalizeQuickstartBoxes ();
	transformSearchbox ();
	transformLanguageSelection ();

	$("#toggle_screenshots").toggle(function(event){
		showScreenshots();
		event.preventDefault();
	},function(event){
		hideScreenshots();
		event.preventDefault();
	});
});