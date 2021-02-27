$(document).ready(function()
{"use strict";var menuActive=false;var header=$('.header');var map;setHeader();initCustomDropdown();initPageMenu();initGoogleMap();$(window).on('resize',function()
{setHeader();});function setHeader()
{if(window.innerWidth>991&&menuActive)
{closeMenu();}}
function initCustomDropdown()
{if($('.custom_dropdown_placeholder').length&&$('.custom_list').length)
{var placeholder=$('.custom_dropdown_placeholder');var list=$('.custom_list');}
placeholder.on('click',function(ev)
{if(list.hasClass('active'))
{list.removeClass('active');}
else
{list.addClass('active');}
$(document).one('click',function closeForm(e)
{if($(e.target).hasClass('clc'))
{$(document).one('click',closeForm);}
else
{list.removeClass('active');}});});$('.custom_list a').on('click',function(ev)
{ev.preventDefault();var index=$(this).parent().index();placeholder.text($(this).text()).css('opacity','1');if(list.hasClass('active'))
{list.removeClass('active');}
else
{list.addClass('active');}});$('select').on('change',function(e)
{placeholder.text(this.value);$(this).animate({width:placeholder.width()+'px'});});}
function initPageMenu()
{if($('.page_menu').length&&$('.page_menu_content').length)
{var menu=$('.page_menu');var menuContent=$('.page_menu_content');var menuTrigger=$('.menu_trigger');menuTrigger.on('click',function()
{if(!menuActive)
{openMenu();}
else
{closeMenu();}});if($('.page_menu_item').length)
{var items=$('.page_menu_item');items.each(function()
{var item=$(this);if(item.hasClass("has-children"))
{item.on('click',function(evt)
{evt.preventDefault();evt.stopPropagation();var subItem=item.find('> ul');if(subItem.hasClass('active'))
{subItem.toggleClass('active');TweenMax.to(subItem,0.3,{height:0});}
else
{subItem.toggleClass('active');TweenMax.set(subItem,{height:"auto"});TweenMax.from(subItem,0.3,{height:0});}});}});}}}
function openMenu()
{var menu=$('.page_menu');var menuContent=$('.page_menu_content');TweenMax.set(menuContent,{height:"auto"});TweenMax.from(menuContent,0.3,{height:0});menuActive=true;}
function closeMenu()
{var menu=$('.page_menu');var menuContent=$('.page_menu_content');TweenMax.to(menuContent,0.3,{height:0});menuActive=false;}
function initGoogleMap()
{var myLatlng=new google.maps.LatLng(51.507098,-0.126270);var mapOptions={center:myLatlng,zoom:14,mapTypeId:google.maps.MapTypeId.ROADMAP,draggable:true,scrollwheel:false,zoomControl:true,zoomControlOptions:{position:google.maps.ControlPosition.RIGHT_CENTER},mapTypeControl:false,scaleControl:false,streetViewControl:false,rotateControl:false,fullscreenControl:true,styles:[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text","stylers":[{"color":"#858585"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#858585"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#ededed"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]}
map=new google.maps.Map(document.getElementById('map'),mapOptions);var image='images/marker.png';var marker=new google.maps.Marker({position:myLatlng,map:map,icon:image});google.maps.event.addDomListener(window,'resize',function()
{setTimeout(function()
{google.maps.event.trigger(map,"resize");map.setCenter(myLatlng);},1400);});}});