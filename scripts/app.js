
import Filter from './modules/filter.js'

new Filter(document.querySelector('.js-filter'))


/*(function ($) {
  $('#filterName').keyup(function (event) {
    var input = $(this);
    var val = input.val();
    var regexp = '\\b(.*)(a)(.*)(k)(.*)\\b';
    $('#filter').find('span').each(function () {
      var span = $(this);
      console.log(span)
      var resultats = span.text().match(new RegExp(regexp, 'i'));
      if (resultats) {
        var string = '';

        for (var i in resultats) {
          if (i > 0) {
            if (i % 2 == 0) {
              string += '<span class="highlighted">' + resultats[i] + '</span>';

            } else {
              string += resultats[i];

            }

          }
        }
        span.empty().append(string);
      } else {
        span.parent().parent().parent().hide();
      }
    })
  });
})(jQuery);*/