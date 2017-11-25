function loadItems(element, path, selectInputClass) {
  var selectedVal = $(element).val();

  // select all
  if (selectedVal == -1) {
    return;
  }

  $.ajax({
  type: 'GET',
  url: path + selectedVal,
  success: function (datas) {
    if (!datas || datas.length === 0) {
       return;
    }

    for (var  i = 0; i < datas.length; i++) {
      $(selectInputClass).append($('<option>', {
        value: datas[i].id,
        text: datas[i].name
    }));
    }
  },
  error: function (ex) {
  }
  });
}

function loadStates(element) {
  $('.js-states').empty().append('<option value="-1">Please select your state</option>');
  $('.js-cities').empty().append('<option value="-1">Please select your city</option>');
  loadItems(element, '../api/states/', '.js-states');
}

function loadCities(element) {
  $('.js-cities').empty().append('<option value="-1">Please select your city</option>');;
  loadItems(element, '../api/cities/', '.js-cities');
}

function registerEvents() {
  $('.js-country').change(function() {
    loadStates(this);
  });

  $('.js-states').change(function() {
    loadCities(this);
  });
}

function init() {
  registerEvents();
}

init();