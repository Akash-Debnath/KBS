/*!  Plugin: treeNav (Tree View from HTML Nested List)
 *   Author: Asif Mughal
 *	 Customize By: Enamul Kabir
 *   URL: www.codehim.com
 *   License: MIT License
 *   Copyright (c) 2019 - Asif Mughal
 */
/* File: jquery.treenav.js */
(function ($) {
	$.fn.treeNav = function () {

		return this.each(function () {

			let target = $(this);
			let listWithSubmenu = $(target).find("li[data-type='sub-menu']");
			let listWithSubmenuAnchor = $(target).find("li[data-type='sub-menu'] > a");

			let plusMinusIcon = document.createElement("span"); //creates span element for sub-menu 

			$(plusMinusIcon).addClass("sub-menu").prependTo(listWithSubmenuAnchor); //add to each li element that has data-type sub-menu attribute 

			// $("li[data-type='sub-menu'] > a").on('click', function(){

			// 	$(this).toggleClass("open");
				
			// 	let subItems = $(this).siblings("ul");
				
			// 	$(subItems).slideToggle("slow");

			// });

			$(listWithSubmenu).children('a').on('click', function(event){
				event.preventDefault();
				$(this).toggleClass('open').next('.sub-items').slideToggle(200).end().parent(listWithSubmenu).siblings(listWithSubmenu).children('a').removeClass('open').next('.sub-items').slideUp(200);
			});

		});
	};

});
