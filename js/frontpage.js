var browser;var browserVersion;var isIE;var screenshotGalleryInitialized=0;var reflectionsInitialized=0;transformSearchbox=function(){if(isIE&&browserVersion==6){return;}
$('#search form span').remove();$("#search form select").css('width','30px');$("#search form select").selectBox({css:'select'});$("#search form div:first").addClass('searchbox');var searchLabel=$("#search form div p").html();$("#search form input.textfield").attr('value',searchLabel);$("#search form input.textfield").addClass('transformed');$("#search form input.textfield").addClass('hint');$("#search form input.textfield").click(function(){$(this).removeClass('hint');$(this).attr('value','');});$("#search form div.select p").html('&nbsp;');$("#search form div.select p").addClass('green');$("#search form .button").css('display','none');$("#search form .searchbutton").css('display','inline');$("#search form li").not("li:contains('oftware')").addClass('green');$("#search form li:contains('oftware')").addClass('orange');$("#search form li:contains('logiciels')").addClass('orange');}
transformLanguageSelection=function(){var currentLanguageAbbrev=$('#i18n option:first').attr('value');$('#i18n option:first').remove();$('#i18n option:first').remove();$('#i18n option[value='+currentLanguageAbbrev+']').attr('selected','selected');$('#i18n #language').change(function(){$('#i18n form:first').submit();});}
browserDetection=function(){browser=navigator.userAgent.toLowerCase();browserVersion=parseFloat(browser.substring(browser.indexOf('msie ')+5));isIE=((browser.indexOf("msie")!=-1)&&(browser.indexOf("opera")==-1)&&(browser.indexOf("webtv")==-1));}
numberComparisonDescending=function(a,b){return b-a;}
equalizeQuickstartBoxes=function(){if(isIE)return;var height1=$('#get a .buttontext').height().replace('px','');var height2=$('#discover a .buttontext').height().replace('px','');var height3=$('#create a .buttontext').height().replace('px','');var heights=new Array(height1,height2,height3);heights.sort(numberComparisonDescending);$('.buttontext').height(heights[0]+'px');}
initializeGallery=function(data){var markup;$('#screenshots').html('<ul id="screenshot_gallery" class="jcarousel-skin-opensuse"></ul>');$.each(data,function(i,screenshot){markup='<li><a href="'+screenshot.file_large+'" title="'+screenshot.description+'" rel="group1"><img src="'+screenshot.file_small+'" width="200" height="150" /></a></li>';$('#screenshot_gallery').append(markup);});$('#screenshot_gallery').jcarousel();$("#screenshots a").fancybox({'overlayShow':true});screenshotGalleryInitialized=1;}
showScreenshots=function(){$("#toggle_screenshots").blur();if(screenshotGalleryInitialized==0){$.getJSON('../screenshot_gallery/cached_screenshots.json',initializeGallery);}
$('#screenshots').slideToggle('slow',function(){if(reflectionsInitialized==0){$('#screenshot_gallery img').reflect();reflectionsInitialized=1;}});$("#toggle_screenshots").addClass('expanded');$("#toggle_screenshots span").html('&uarr; Screenshots');}
hideScreenshots=function(){$("#toggle_screenshots").blur();$('#screenshots').slideToggle('slow');$("#toggle_screenshots").removeClass('expanded');$("#toggle_screenshots span").html('&darr; Screenshots');}
fixIE6alphaTransparency=function(){if(isIE&&browserVersion==6){$('#quicklinks .top').each(pngfix);$('#quicklinks .buttontext').each(pngfix);$('#quicklinks .bottom').each(pngfix);$('#shop img').each(pngfix);}}
$(document).ready(function(){browserDetection();fixIE6alphaTransparency();equalizeQuickstartBoxes();transformSearchbox();transformLanguageSelection();$("#toggle_screenshots").toggle(function(event){showScreenshots();event.preventDefault();},function(event){hideScreenshots();event.preventDefault();});});

// inject piwik counter
var _paq = _paq || [];
(function(){
var u=(("https:" == document.location.protocol) ? "https://beans.opensuse.org/piwik/" : "http://beans.opensuse.org/piwik/");
_paq.push(['setSiteId', 10]);
_paq.push(['setTrackerUrl', u+'piwik.php']);
_paq.push(['trackPageView']);
_paq.push([ 'setDomains', ["*.opensuse.org"]]);
var d=document,
g=d.createElement('script'),
s=d.getElementsByTagName('script')[0];
g.type='text/javascript';
g.defer=true;
g.async=true;
g.src=u+'piwik.js';
s.parentNode.insertBefore(g,s);
})();

// inject banner link
$(document).ready(function() {
  $("#intro > a").replaceWith( "<a href='http://counter.opensuse.org/link/'><h1 title='openSUSE'><span>openSUSE</span></h1></a>" );
});

// inject changed de wiki link
$(document).ready(function() {
  var delink = $("#discover > a").attr('href').replace(/dewiki\.opensuse/, 'de.opensuse');
  $("#discover > a").attr('href', delink);
});

// sopa strike
var a=new Date;
if(18==a.getDate()&&0==a.getMonth()&&2012==a.getFullYear())window.location="http://sopastrike.com/strike";

