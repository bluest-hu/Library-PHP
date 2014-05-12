// 处理
$(function() {
    var closeBtn = $(".message .close-btn");
    if (closeBtn) {
        closeBtn.on("click", function () {
            closeBtn.parent(".message").slideUp();
        });
    }
});


 // 滑动按钮
($(function() {

	if (!$(".switcher")) {
		return;
	}

	var moveDis = parseInt($(".switcher").css("width"));  
	var $indicator = $(".switcher .indicator");

	// reset width
	$indicator.css({"width": moveDis / 2 * 3});

	var remberme = document.getElementById('remberme');


	$(".switcher .container .left-area").on(
		"click", 
		function (event) {
			$indicator.animate({
				"left": 0}, 
				"fast", 
				"linear", 
				function () {
			    	remberme.value  = "on";
				});

			event = event||window.event;
			event.preventDefault(); 
			});

	$(".switcher .container .right-area").on("click", function() {
		$indicator.animate(
			{
				"left": - moveDis / 2  
			}, 
			"fast", 
			"linear", 
			function () {
	    		remberme.value  = "off";
			});
		event = event||window.event;
		event.preventDefault(); 
	});

}));

//处理NumberPicker
$(function () {
	$numberPicks = $(".number-picker");
	if (!$numberPicks) {
		return;
	}

	$numberPicks.each(function (index, element) {
		$numberPick = $(element);
		$reduceBtn = $numberPick.find(".reduce-number-btn");
		$addBtn = $numberPick.find(".add-number-btn");

		$reduceBtn.on("click", function(event){

			var $numberShow = $(this).siblings(".number-text");

			var value = parseInt($numberShow.val());

			// 处理非数字
			value = isNaN(value) ? 0 : value;
			
			value--;

			value = value < 0 ? 0 : value;
			
			$numberShow.val(value);
			$numberShow.attr("value", value);

			event = window.event || event;
			event.preventDefault();
			 event.stopPropagation();
			return false;
		}).on("dblclick", function(event) {

			event = window.event || event;
			event.preventDefault();
		 	event.stopPropagation();
			return false;
		});
		
		$addBtn.on("click", function(event) {
	
			var $numberShow = $(this).siblings(".number-text");

			var value = parseInt($numberShow.val());
			value = isNaN(value) ? 0 : value;

			value++;
			
			$numberShow.val(value);
			$numberShow.attr("value", value);

			event = window.event || event;
			event.preventDefault();
			event.stopPropagation();
			return false;

		}).on("dblclick", function(event) {

			event = window.event || event;
			event.preventDefault();
			event.stopPropagation();
			return false;
		});


		$numberPick.find(".number-text").on("blur", function() {
			var value = parseInt($(this).val());
			
			$(this).attr("value", value);	
		});
	});
});


//ajust user nav 

$(function() {
	$nav = $(".content .user-nav");
	
	if ($nav) {
		$nav.css({
			"height": $("#mianContent").css("height")
		});
	}
});