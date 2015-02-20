//Cast a shadow before unload to tell the user the page is loading
$(window).on('beforeunload', function(){
	$("body").append($("<div>").css({
		position: "fixed"
		,width: "100%"
		,height: "100%"
		,"background-color": "#000"
		,opacity: 0.6
		,"z-index": 999
		,top: 0
		,left: 0
	}).attr("id","page-cover"));

});
