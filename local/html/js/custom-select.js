////// select //////
$(".custom-select").each(function() {
	let attr = $(this).find('select').attr('name');
	let template =  '<div class="custom-select__content">';
	if (typeof attr !== typeof undefined && attr !== false) {
		template += '<div class="custom-select__selected custom-select__selected_name">' + attr + '</div>';
	} else {
		template += '<div class="custom-select__selected">' + $(this).find('select').children('option').eq(0).text() + '</div>';
	}

	template += '<div class="custom-select__options">';
	$(this).find("option").each(function() {
		template += '<div class="custom-select__option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</div>';
	});
	template += '</div></div>';

	$(this).append(template);
});

$('.custom-select__selected').each(function () {
	$(this).on("click", function(e) {
		$('html').one('click',function() {
			$(".custom-select__options").slideUp('fast');
			$('.custom-select__selected').removeClass('active');
		});
		$(this).next('.custom-select__options').slideToggle('fast');
		$(this).toggleClass('active');

		e.stopPropagation();
	});
})

$(".custom-select__option").each(function () {
	$(this).on("click", function() {
		$(this).parents(".custom-select__wrapper").find("select").val($(this).data("value"));
		$(this).parents(".custom-select__options").find(".custom-select__option").removeClass("selection");
		$(this).addClass("selection");
		$(this).parents(".custom-select__options").slideUp('fast');
		$(this).parents(".custom-select__content").find(".custom-select__selected").text($(this).text());

		if ($(this).parent().parent().find('.custom-select__selected_name').html() !== $(this).parent().parent().parent().find('select').attr('name')) {
			$(this).parent().parent().find('.custom-select__selected_name').removeClass('custom-select__selected_name')
		}
	});
})
////// ---select--- //////