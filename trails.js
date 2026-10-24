document.addEventListener('DOMContentLoaded', function () {
  var searchInputs = [
    document.getElementById('main-search'),
    document.getElementById('search-box')
  ].filter(Boolean);

  var categorySelect = document.getElementById('category');
  var cards = document.querySelectorAll('.trail-card');
  var emptyMsg = document.getElementById('trails-no-results');

  function filterTrails() {
    var query = '';

    searchInputs.forEach(function (input) {
      if (input.value.trim()) {
        query = input.value.trim().toLowerCase();
      }
    });

    var category = categorySelect ? categorySelect.value : 'all';
    var shown = 0;

    cards.forEach(function (card) {
      var text = card.textContent.toLowerCase();
      var matchesText = !query || text.includes(query);
      var matchesCategory =
        category === 'all' || card.dataset.category === category;
      var visible = matchesText && matchesCategory;

      card.style.display = visible ? '' : 'none';
      if (visible) shown++;
    });

    if (emptyMsg) {
      emptyMsg.hidden = shown > 0;
    }
  }

  searchInputs.forEach(function (input) {
    input.addEventListener('input', filterTrails);
  });

  if (categorySelect) {
    categorySelect.addEventListener('change', filterTrails);
  }

  filterTrails();

  var bookingForm = document.getElementById('booking-form');
  var totalElement = document.getElementById('trails-total');
  var confirmation = document.getElementById('trails-confirmation');

  function updateTotal() {
    if (!bookingForm || !totalElement) return;

    var selected = bookingForm.querySelector(
      'input[name="booking_type"]:checked'
    );

    var amount = selected && selected.value === 'monthly' ? 8400 : 350;
    totalElement.textContent =
      'Total: RM ' + amount.toLocaleString('en-MY');
  }

  if (bookingForm) {
    bookingForm.querySelectorAll(
      'input[name="booking_type"]'
    ).forEach(function (radio) {
      radio.addEventListener('change', updateTotal);
    });

    updateTotal();

    bookingForm.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!bookingForm.reportValidity()) return;

      var formData = new FormData(bookingForm);
      var name = String(formData.get('name') || '').trim();
      var bookingType = formData.get('booking_type');
      var amount = bookingType === 'monthly' ? 8400 : 350;

      if (confirmation) {
        confirmation.textContent =
          'Thank you, ' + name +
          '! Your ' + bookingType +
          ' booking request has been received for ' +
          'RM ' + amount.toLocaleString('en-MY') +
          '. This is a demo confirmation; no booking has been saved.';
        confirmation.hidden = false;
        confirmation.setAttribute('role', 'status');
        confirmation.setAttribute('aria-live', 'polite');
      }
    });
  }
});
