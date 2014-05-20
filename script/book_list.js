
$(function () {
	updateNavHeight();
	
});


function updateNavHeight() {
	var mainHeight = $("#mianContent").height() - 2;
	var navHeight = $("#bookCateNav").height();
	// var height = mainHeight > navHeight ? mainHeight : navHeight;
	$("#bookCateNav").css("height", mainHeight);
}


function dealUnkonwText(text) {
	return (jQuery.trim(text) === "")  ? "未知" : text;
}


$(function() {


	// 插入的行数
	var $books = $(".books-list-container .book");
	var sumCount = $books.length;

	// 插入的第几行
	var appendRowIndex = 0;

	// 上一次插入位置缓存
	var lastappendBookIndex = null;
	// 每行的图书数目

	var EACH_COLUMN_COUNT = 6;
	//插入的书的索引
	var appendBookIndex = 0;

	// 缓存 上次点击的图书索引 
	var lastClickedBookIndex = null;

	// 详情窗口是否打开
	var isSlideDown = false;


	var __html = 	 
	'<li class="expland">' +
		'<span class="arrow"></span>' +
		'<span class="close-btn">X</span>' +
		'<div class="book-detail clear">' +
		'</div>' +
	'</li>';


	var $html = $(__html);
	var $arrow = $html.find(".arrow");
	var $closeBtn = $html.find(".close-btn");
	var $content = $html.find(".book-detail"); 

	$books.on("click", function () {
		var __this = $(this);

		$.ajax({
			url:$(this).find(".book-name").attr('href'),
			method:"GET",
			async:false,
			complete:function (data) {
				var json = eval("(" + data.responseText + ")");
				var __content = 
"<div class='column-one book-cover left'>"+
	"<img src='"+json.cover+"'/>" +
"</div>"  +
"<div class='column-two left'>" +
	"<h4 class='title'>图书信息</h4>" +
	"<p>" + "<b>书名：</b>《" +json.name + "》</p>" +
	"<p>" + "<b>出版社：</b>" +  dealUnkonwText(json.publisher) + "</p>" +
	"<p>" +"<b>作者：</b>" + dealUnkonwText(json.author) + "</p>" +
	"<p>" +"<b>分类：</b>" + dealUnkonwText(json.cate) + "</p>" +
"</div>" +
"<div class='column-three left' style='clear:right'>" +
	"<h4 class='title'>图书简介</h4>" +
	"<div>" + dealUnkonwText(json.summary)  + "</div>"
"</div>";
				$content.html(__content);
				
			}
		});

		$html.css("display", "none");

		if (lastappendBookIndex != appendBookIndex) {
		} 

		// 计算要插入的一行最后一本图书的索引
		var bookIndex 	= __this.index();
		appendRowIndex 	= parseInt((bookIndex) / EACH_COLUMN_COUNT ) + 1;
		appendBookIndex = appendRowIndex * EACH_COLUMN_COUNT - 1;

		// 偏移量 简单粗暴 不计算了 
		// 我懒 我懒 我懒 还写爱出 shit 一样的代码
		// 看 它们长得一坨坨的~~
		var arrowToLeftDis = $(this).offset().left - $books.eq(0).offset().left + 60;

		// 如果这一行是满的 那就插入最后一行
		// 不满的话那就最后一个咯，说明这一排是最后一排（这当然是句废话）
		if ($books.get(appendBookIndex)){
			$books.eq(appendBookIndex).after($html);
		} else {
			appendBookIndex = sumCount - 1;
			$books.eq(appendBookIndex).after($html);
		}
		
		// 如果点击的图书是同一行的，那么就不用slideDown特效了
		if (lastappendBookIndex != appendBookIndex) {
			$html.slideDown();
		} else {
			$html.css("display", "block");
		}

		$arrow.animate({"left": arrowToLeftDis}, function () {
			updateNavHeight();
		});

		lastappendBookIndex = appendBookIndex;
		lastClickedBookIndex  = bookIndex;

	});


	$closeBtn.on("click", function () {
		$html.slideUp();
		lastappendBookIndex = null;
		updateNavHeight();
	});
});